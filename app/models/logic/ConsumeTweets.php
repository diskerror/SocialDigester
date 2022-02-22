<?php

namespace Logic;

use Diskerror\TypedBSON\DateTime;
use Ds\Vector;
use Exception;
use Logic\Tally\Hashtags;
use Logic\Tally\Retweets;
use Logic\Tally\TextWords;
use Logic\Tally\UserMentions;
use Logic\Tally\Users;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Driver\WriteConcern;
use Resource\LoggerFactory;
use Resource\MongoCollections\Messages;
use Resource\MongoCollections\Tallies;
use Resource\MongoCollections\Tweets;
use Resource\PidHandler;
use Resource\TwitterV1;
use Service\Shmem;
use Service\ShmemMaster;
use Service\StdIo;
use Structure\Config;
use Structure\SearchTerms;
use Structure\Tally;
use Structure\Tweet;

final class ConsumeTweets
{
	//	512 meg memory limit
	const MEMORY_LIMIT = 512 * 1024 * 1024;
	const INSERT_COUNT = 64;    //	best values are powers of 2

	private final function __construct() { }

	/**
	 * Open and save a stream of tweets.
	 *
	 * @param Config $config
	 */
	public static function exec(Config $config)
	{
		mb_internal_encoding('UTF-8');
		ini_set('memory_limit', self::MEMORY_LIMIT);

		$pidHandler = new PidHandler($config->process);

		$logger = new LoggerFactory(BASE_PATH . '/consume.log');
//		$logger = new LoggerFactory('php://stderr');

		$twitter       = new TwitterV1($config->twitter->auth);
		$tweetsMongo   = new Tweets($config->mongo_db);
		$talliesMongo  = new Tallies($config->mongo_db);
		$messagesMongo = new Messages($config->mongo_db);

		$waitMem   = new ShmemMaster('w');    //	wait between saves
		$rateMem   = new ShmemMaster('r');    //	rate which good tweets are received
		$rateTime  = microtime(true);
		$waitTotal = 0;

		try {
			//	Send request to start a filtered stream.
			$sBuffer = $twitter->stream([
				'track'          => SearchTerms::implode(),
				'language'       => 'en',
				'stall_warnings' => true,
			]);

			//	Set PID file to indicate whether we should keep running.
			if ($pidHandler->setFile() === false) {
				$logger->error('Process "' . $config->process->path . '/' . $config->process->name . '" is already running or not stopped properly');
				return;
			}

			$insertOptions = ['writeConcern' => new WriteConcern(0, 100, false)];

			//	Announce that we're running.
			$logger->info('Started capturing tweets.');

			$tweets = new Vector();
			$tweets->allocate(self::INSERT_COUNT);

			while ($pidHandler->exists() && !$sBuffer->isEOF()) {
				$tweets->clear();
				$tally          = new Tally();
				$tally->created = new DateTime();

				$i = 0;
				while ($i < self::INSERT_COUNT && $pidHandler->exists() && !$sBuffer->isEOF()) {
					//	get tweet
					$startWait = microtime(true);
					$raw       = $sBuffer->read();

					//	Check for any data at all.
					//	use "match" in PHP8
					if ($raw === '' || $raw === false || $raw === null) {
						continue;
					}

					$raw = trim($raw, "\x00..\x20\x7F");

					//	If we don't receive an object in JSON then this must be plain text.
					if ($raw[0] !== '{') {
						$logger->emergency($raw);
						$logger->warning($raw);
						$pidHandler->removeIfExists();
						return;
					}

					$packet = json_decode($raw, true);

					//	Ignore nulls and falses and empties.
					//	use "match" in PHP8
					if ($packet === false || $packet === null || $packet === '' || count($packet) === 0) {
						continue;
					}

					//	Log bad data.
					if (!is_array($packet)) {
						$logger->info('bad packet' . PHP_EOL . var_export($packet, true));
						continue;
					}

					//	Save Twitter messages.
					//	Packet _id is time in ms.
					if ($twitter::isMessage($packet)) {
						$packet['created'] = new UTCDateTime((microtime(true) * 1000));
						try {
							$messagesMongo->insertOne($packet);
						}
						catch (Exception $e) {
							$logger->emergency(
								'Mongo insert into messages: ' . $e->getMessage() . PHP_EOL .
								json_encode($packet, JSON_PRETTY_PRINT)
							);
						}
						continue;
					}

					//	Prune. Our Tweet structure accepts only part of the packet.
					$tweet = new Tweet($packet);

					//	If tweet is not in english then skip it.
					if ($tweet->lang !== 'en') {
						$logger->info('packet lang not en');
						continue;
					}

					++$i;    //	increment only for tweets to be saved

					$waitTotal += (microtime(true) - $startWait);

					// Check for and use extended tweet if it exists.
					if (strlen($tweet->extended_tweet->full_text) > strlen($tweet->text)) {
						$tweet->text = $tweet->extended_tweet->full_text;
						unset($tweet->extended_tweet->full_text);

						$tweet->entities = $tweet->extended_tweet->entities;
						unset($tweet->extended_tweet->entities);
					}

					//	Do pre-tallies.
					Hashtags::pre($tweet, $tally);
					//	HashtagsAll::pre() is handled in Hashtags::pre().
					TextWords::pre($tweet, $tally);
					UserMentions::pre($tweet, $tally);
					Users::pre($tweet, $tally);
					Retweets::pre($tweet, $tally);

					//	Save tweet for full-text analysis.
					$tweets->push($tweet->bsonSerialize());
				}

				try {
					//	convert to Mongo compatible object and insert
					$tweetsMongo->insertMany($tweets->toArray(), $insertOptions);
					$talliesMongo->insertOne($tally->bsonSerialize(), $insertOptions);
				}
				catch (Exception $e) {
					$m = $e->getMessage();

					if (preg_match('/Authentication/i', $m)) {
						$logger->emergency('Mongo ' . $m);
					}
					else {
						//	ignore duplicates but log everything else
//						if (!preg_match('/duplicate.*key/i', $m)) {
						$logger->warning('Mongo: ' . $m);
//						}
					}
					exit;
				}

				$now = microtime(true);
				$rateMem->write(self::INSERT_COUNT / ($now - $rateTime));
				$rateTime = $now;

				//	average wait to read pack per inner loop
				$waitMem->write($waitTotal / self::INSERT_COUNT);
				$waitTotal = 0;
			}

			$logger->info('Stopped capturing tweets.');
		}
		catch (Exception $e) {
			$logger->emergency((string) $e);
		}
	}

	public static function isRunning(int $maxSecs)
	{
		try {
			return (new Shmem('w'))() < 6 ? 1 : 0;
		}
		catch (Exception $e) {
			//	If not open then process isn't running.
			if (strstr($e->getMessage(), 'could not open or create shared memory')) {
				return 0;
			}
			else {
				throw $e;
			}
		}
	}

}
