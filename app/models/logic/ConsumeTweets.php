<?php

namespace Logic;

use Diskerror\TypedBSON\BsonOptions;
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
use Resource\MongoCollection;
use Resource\PidHandler;
use Resource\TwitterV1;
use Service\Exception\ShmemOpenException;
use Service\Shmem;
use Service\ShmemMaster;
use Structure\Config;
use Structure\Tally;
use Structure\Tweet;

final class ConsumeTweets
{
	//	512 meg memory limit
	const MEMORY_LIMIT = 256 * 1024 * 1024;
	const INSERT_COUNT = 32;    //	best values are powers of 2

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

		$logger = new LoggerFactory($config->basePath . '/consume.log');
//		$logger = new LoggerFactory('php://stderr');

		try {
			//	Set PID file to indicate whether we should keep running.
			$pidHandler = new PidHandler($config->process);
			if ($pidHandler->setFile() === false) {
				$logger->warning('Process "' . $config->process->path . $config->process->name . '" is already running or not stopped properly.');
				$pidHandler->removeIfExists();
				sleep(1);
				if ($pidHandler->setFile() === false) {
					throw new Exception('Problem setting PID file.');
				}
			}

			$twitter      = new TwitterV1($config->twitterOAuth);
			$tweetsMongo  = new MongoCollection($config, 'tweets');
			$talliesMongo = new MongoCollection($config, 'tallies');
//			$messagesMongo = new MongoCollection($config, 'messages');
			$insertOptions = ['writeConcern' => new WriteConcern(0, 100, false)];

			$waitMem   = new ShmemMaster('w');    //	wait between saves
			$rateMem   = new ShmemMaster('r');    //	average rate which good tweets are received
			$rateIMem  = new ShmemMaster('i');    //	instantainious rate
			$rateTime  = microtime(true);
			$waitTotal = 0;

			$tweets = new Vector();
			$tweets->allocate(self::INSERT_COUNT);

			//	Send request to start a filtered stream.
			$twitter->stream([
				'track'          => implode(',', require $config->configPath . '/SearchTerms.php'),
				'language'       => 'en',
				'stall_warnings' => true,
			]);

			//	Announce that we're running.
			$logger->info('Started capturing tweets.');

			while ($pidHandler->exists() && !$twitter->streamEOF()) {
				$tweets->clear();
				$tally = new Tally();
				$tally->toBsonOptions->add(BsonOptions::CAST_ID_TO_OBJECTID);

				$ic = 0;
				while ($ic < self::INSERT_COUNT && $pidHandler->exists() && !$twitter->streamEOF()) {
					$startWait = microtime(true);

					//	returns an associative array
					$packet = $twitter->getPacket();

					if ($packet === '') {
						continue;
					}

					if (is_scalar($packet) or null === $packet) {
						/**
						 * If we don't receive an object then this is likely
						 * a plain text message telling us to stop.
						 */
						throw new Exception(json_encode($packet, JSON_PRETTY_PRINT));
					}

					//	Save Twitter messages.
					//	Packet _id is time in ms.
					if ($twitter::isMessage($packet)) {
						$packet['created'] = new UTCDateTime();

						try {
//							$messagesMongo->insertOne($packet);
							$logger->info(json_encode($packet));
						}
						catch (Exception $e) {
							$logger->emergency(
								$e->getMessage() . PHP_EOL .
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

					++$ic;    //	increment only for tweets to be saved

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
					$tweets->push($tweet);
				}

				try {
					//	convert to Mongo compatible object and insert
					$tweetsMongo->insertMany($tweets->toArray(), $insertOptions);
					$talliesMongo->insertOne($tally, $insertOptions);
				}
				catch (Exception $e) {
					$m = $e->getMessage();

					if (preg_match('/Authentication/i', $m)) {
						throw new Exception('Mongo ' . $m);
					}
					else {
						//	ignore duplicates but log everything else
//						if (!preg_match('/duplicate.*key/i', $m)) {
						$logger->warning('Mongo: ' . $m);
//						}
					}
					exit;
				}

				$now         = microtime(true);
				$currentRate = self::INSERT_COUNT / ($now - $rateTime);
				$rateIMem->write($currentRate);
				$rateMem->write((0.8 * (float) $rateMem->read()) + (0.2 * $currentRate));
				$rateTime = $now;

				//	average wait to read pack per inner loop
				$waitMem->write($waitTotal / self::INSERT_COUNT);
				$waitTotal = 0;
			}
		}
		catch (Exception $e) {
			$logger->emergency((string) $e);
		}

		$pidHandler->removeIfExists();
		$logger->info('Stopped capturing tweets.');
	}

	public static function detectRunning(): int
	{
		try {
			return (new Shmem('w'))() < 6 ? 1 : 0;
		}
		catch (ShmemOpenException $e) {
			return 0;
		}
	}

}
