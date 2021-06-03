<?php

namespace Code;

use Ds\Set;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Driver\WriteConcern;
use Phalcon\Config;
use Resource\Messages;
use Resource\Tweets;
use Resource\TwitterClient\Stream;
use Structure\Tallies;
use Structure\Tweet;
use function str_split;
use function var_dump;
use function var_export;
use const PHP_EOL;

final class ConsumeTweets
{
	//	512 meg memory limit
	const MEMORY_LIMIT = 512 * 1024 * 1024;
	const INSERT_COUNT = 16;

	private function __construct() { }

	/**
	 * Open and save a stream of tweets.
	 *
	 * @param Config     $tconfig
	 * @param PidHandler $pid_handler
	 *
	 */
	public static function exec(Config $config)
	{
		ini_set('memory_limit', self::MEMORY_LIMIT);

		try {
			$stream     = new Stream($config->twitter->auth);
			$pidHandler = new PidHandler($config->process);

//			$logger = LoggerFactory::getFileLogger(APP_PATH . '/' . $config->process->name . '.log');
			$logger = LoggerFactory::getStreamLogger();

//			$sh = new StemHandler();

			$tweetsClient   = (new Tweets())->getClient();
			$talliesClient  = (new \Resource\Tallies())->getClient();
			$messagesClient = (new Messages())->getClient();


			//	Send request to start a filtered stream.
			$stream->filter([
				'track'          => implode(',', $config->twitter->track->toArray()),
				'language'       => 'en',
				'stall_warnings' => true,
			]);

			//	Set PID file to indicate whether we should keep running.
			$pidHandler->setFile();

			$insertOptions = ['writeConcern' => new WriteConcern(0, 100, false)];

			$stopWords = $config->word_stats->stop->toArray();

			//	Announce that we're running.
//			$logger->info('Started capturing tweets.');
			echo 'Started capturing tweets.' . PHP_EOL;	//	TODO: fix

			while ($pidHandler->exists() && !$stream->isEOF()) {
				$tweets  = [];
				$tallies = new Tallies();
				for ($i = 0; $i < self::INSERT_COUNT; ++$i) {
					//	get tweet
					try {
						$packet = $stream->read();
					}
					catch (\Exception $e) {
						$logger->info((string)$e);
						continue;
					}

					//	Ignore bad data.
					if (!is_array($packet)) {
						continue;
					}

					if ($stream::isMessage($packet)) {
						$packet['created'] = new UTCDateTime();
						$messagesClient->insertOne($packet);
						continue;
					}

					//	Filter. Use only part of returned structure.
					$tweet = new Tweet($packet);

					//	If tweet is not in english then skip it.
					if ($tweet->lang !== 'en') {
						continue;
					}

					//	Pre calculate tallies for INSERT_COUNT of tweets.
					$uniqueWords = new Set();
					//	Make sure we have only one of a hashtag per tweet for uniqueHashtags.
					foreach ($tweet->entities->hashtags as $hashtag) {
						$text = str_split($hashtag->text);
						foreach ($text as $t) {
							if ($t & chr(0x80)) {
								continue 2;	//	skip hashtag if it contains a non-ASCII byte
							}
						}
						$uniqueWords->add($hashtag->text);
						$tallies->allHashtags->doTally($hashtag->text);
					}

					//	Count unique hashtags for this tweet.
					foreach ($uniqueWords as $uniqeWord) {
						$tallies->uniqueHashtags->doTally($uniqeWord);
					}

					//	Tally the words in the text.
					$text  = preg_replace('#https?:[^ ]+#', '', $tweet->text);
					$split = preg_split('/[^a-zA-Z0-9\']/', $text, null, PREG_SPLIT_NO_EMPTY);
					foreach ($split as $s) {
						if (strlen($s) > 2 && !in_array(strtolower($s), $stopWords)) {
							$tallies->textWords->doTally($s);
						}
					}

					//	Tally user mentions.
					foreach ($tweet->entities->user_mentions as $userMention) {
						$tallies->userMentions->doTally($userMention->screen_name);
					}

					//	remove URLs from text
//				$text  = preg_replace('#https?:[^ ]+#', '', $tweet->text);
//				$words = [];
//
//				//	build the two stem lists
//				$split = preg_split('/[^a-zA-Z0-9]/', $text, null, PREG_SPLIT_NO_EMPTY);
//				foreach ($split as $s) {
//					$words[] = $sh->get($s);
//				}
//
//				//	build stem pairs
//				$last = '';
//				foreach ($tweet->words as $w) {
//					$tweet->pairs[] = $last . $w;
//					$last           = $w;
//				}
//				$tweet->pairs[] = $last;

					$tweets[] = $tweet->toArray();
				}

				try {
					//	convert to Mongo compatible object and insert
					$tweetsClient->insertMany($tweets, $insertOptions);
					$talliesClient->insertOne($tallies->toArray(), $insertOptions);
				}
				catch (\Exception $e) {
					$m = $e->getMessage();

					if (preg_match('/Authentication/i', $m)) {
//						$logger->emergency('Mongo ' . $m);
						echo 'Mongo emergency ' . $m . PHP_EOL; //	TODO: fix this
					}
					else {
						//	ignore duplicates
						if (!preg_match('/duplicate.*key/i', $m)) {
//							$logger->warning('Mongo ' . $m);
							echo 'Mongo warning ' . $m . PHP_EOL; //	TODO: fix this
						}
					}
				}
			}

//			$logger->info('Stopped capturing tweets.');
			echo 'Stopped capturing tweets.' . PHP_EOL; //	TODO: fix this
		}
		catch (\Exception $e) {
//			$logger->emergency((string)$e);
			echo (string)$e . PHP_EOL; //	TODO: fix this
		}

		$pidHandler->removeIfExists();
	}

}
