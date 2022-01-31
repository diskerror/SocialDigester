<?php

namespace Logic;

use Ds\Set as DsSet;
use Ds\Vector;
use Exception;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Driver\WriteConcern;
use Resource\LoggerFactory;
use Resource\MongoCollections\Messages;
use Resource\MongoCollections\Tallies;
use Resource\MongoCollections\Tweets;
use Resource\PidHandler;
use Resource\TwitterV1;
use Service\SharedTimer;
use Service\ShmemMaster;
use Service\StdIo;
use Structure\Config;
use Structure\Tally;
use Structure\Tweet;
use function microtime;

final class ConsumeTweets
{
	//	512 meg memory limit
	const MEMORY_LIMIT = 512 * 1024 * 1024;
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

		$pidHandler = new PidHandler($config->process);

		$logger = new LoggerFactory(BASE_PATH . '/consume.log');
//		$logger = new LoggerFactory('php://stderr');

//		$sh = new StemHandler();

		$twitter       = new TwitterV1($config->twitter->auth);
		$tweetsMongo   = new Tweets($config->mongo_db);
		$talliesMongo  = new Tallies($config->mongo_db);
		$messagesMongo = new Messages($config->mongo_db);

//		$timer    = new SharedTimer('c');
		$waitMem   = new ShmemMaster('w');    //	wait between saves
		$rateMem   = new ShmemMaster('r');    //	rate which good tweets are received
		$rateTime  = microtime(true);
		$waitTotal = 0;

		try {
			//	Send request to start a filtered stream.
			$stream = $twitter->stream([
				'track'          => implode(',', $config->twitter->track->toArray()),
				'language'       => 'en',
				'stall_warnings' => true,
			]);

			//	Set PID file to indicate whether we should keep running.
			if ($pidHandler->setFile() === false) {
				$logger->error('Process "' . $config->process->path . $config->process->name . '" is already running or not stopped properly');

//				$timer->delete();
				return;
			}

			$insertOptions = ['writeConcern' => new WriteConcern(0, 100, false)];

			$stopWords = new Vector($config->word_stats->stop->toArray());

			//	Announce that we're running.
			$logger->info('Started capturing tweets.');

			$tweets = new Vector();
			$tweets->allocate(self::INSERT_COUNT);

			while ($pidHandler->exists() && !$stream->isEOF()) {
				$tweets->clear();
				$tally = new Tally();

				$i = 0;
				while ($i < self::INSERT_COUNT && $pidHandler->exists() && !$stream->isEOF()) {
					//	get tweet
					$startWait = microtime(true);
					$raw       = $stream->read();

					//	Check for any data at all.
					//	use "match" in PHP8
					if ($raw === '' || $raw === false || $raw === null) {
						continue;
					}

					$raw = trim($raw, "\x00..\x20\x7F");

					//	If we don't receive an object in JSON then this must be plain text.
					if ($raw[0] !== '{') {
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
						$messagesMongo->insertOne($packet);
						continue;
					}

					//	Prune. Our Tweet structure accepts only part of the packet.
					$tweet = new Tweet($packet);

					//	If tweet is not in english then skip it.
					if ($tweet->lang !== 'en') {
						$logger->info('packet lang not en');
						continue;
					}

					//	Skip tweet if it has a hashtag with non-latin scripts.
					foreach ($tweet->entities->hashtags as $hashtag) {
						if (mb_ord(mb_substr($hashtag->text, 0, 1)) > 592) {
//							$logger->info('hashtag script not latin');
							continue 2;
						}
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

					//	Make sure we have only one of a hashtag per tweet for uniqueHashtags.
					$uniqueHashtags = new DsSet();
					foreach ($tweet->entities->hashtags as $hashtag) {
						if ($hashtag->text != '') {
							$uniqueHashtags->add($hashtag->text);
							$tally->allHashtags->doTally($hashtag->text);
						}
					}

					//	Count unique hashtags for this tweet.
					foreach ($uniqueHashtags as $uniqueHashtag) {
						$tally->uniqueHashtags->doTally($uniqueHashtag);
					}

					//	Tally the words in the text.
					$split = preg_split('/[^a-zA-Z0-9_\']/', $tweet->text, null, PREG_SPLIT_NO_EMPTY);
					foreach ($split as $s) {
						if (strlen($s) > 2 && !$stopWords->contains(strtolower($s))) {
							$tally->textWords->doTally($s);
						}
					}

					//	Tally user mentions.
					foreach ($tweet->entities->user_mentions as $userMention) {
						$tally->userMentions->doTally($userMention->screen_name);
					}

//					$words = [];
//
//					//	build the two stem lists
//					$split = preg_split('/[^a-zA-Z0-9]/', $text, null, PREG_SPLIT_NO_EMPTY);
//					foreach ($split as $s) {
//						$words[] = $sh->get($s);
//					}
//
//					//	build stem pairs
//					$last = '';
//					foreach ($tweet->words as $w) {
//						$tweet->pairs[] = $last . $w;
//						$last           = $w;
//					}
//					$tweet->pairs[] = $last;

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
						//	ignore duplicates
						if (!preg_match('/duplicate.*key/i', $m)) {
							$logger->warning('Mongo ' . $m);
						}
					}
				}

//				StdIo::outf("\r%.5f ", $timer->elapsed());
				$now = microtime(true);
				$rateMem->write(self::INSERT_COUNT / ($now - $rateTime));
				$rateTime = $now;
//				$timer->start();

				//	average wait to read pack per inner loop
				$waitMem->write($waitTotal / self::INSERT_COUNT);
				$waitTotal = 0;
			}

			$logger->info('Stopped capturing tweets.');
//			$timer->delete();
		}
		catch (Exception $e) {
			$logger->emergency((string) $e);
		}
	}

}
