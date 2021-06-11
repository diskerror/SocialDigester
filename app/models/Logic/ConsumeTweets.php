<?php

namespace Logic;

use Diskerror\Typed\ArrayOptions as AO;
use Ds\Set;
use Exception;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Driver\WriteConcern;
use Phalcon\Config;
use Resource\Messages;
use Resource\Tallies;
use Resource\Tweets;
use Resource\TwitterClient\Stream;
use Structure\TallySet;
use Structure\Tweet;
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
	 * @param Config $config
	 */
	public static function exec(Config $config)
	{
		ini_set('memory_limit', self::MEMORY_LIMIT);

		$pidHandler = new PidHandler($config->process);

//		$logger = new LoggerFactory(APP_PATH . '/' . $config->process->name . '.log');
		$logger = new LoggerFactory('php://stderr');

//		$sh = new StemHandler();

		try {
			$stream         = new Stream($config->twitter->auth);
			$tweetsClient   = (new Tweets())->getClient();
			$talliesClient  = (new Tallies())->getClient();
			$messagesClient = (new Messages())->getClient();


			//	Send request to start a filtered stream.
			$stream->filter([
				'track'          => implode(',', $config->twitter->track->toArray()),
				'language'       => 'en',
				'stall_warnings' => true,
			]);

			//	Set PID file to indicate whether we should keep running.
			if ($pidHandler->setFile() === false) {
				$logger->error('Process "' . $config->process->path . $config->process->name . '" is already running or not stopped properly');
				return;
			}

			$insertOptions = ['writeConcern' => new WriteConcern(0, 100, false)];

			$stopWords = $config->word_stats->stop->toArray();

			//	Announce that we're running.
			$logger->info('Started capturing tweets.');

			$tweet    = new Tweet();
			$tweet->setArrayOptions(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::NO_CAST_BSON | AO::CAST_DATETIME_TO_BSON);

			$tallySet = new TallySet();

			while ($pidHandler->exists() && !$stream->isEOF()) {
				$tweets = [];
				$tallySet->assign(null);
				for ($i = 0; $i < self::INSERT_COUNT; ++$i) {
					//	get tweet
					try {
						$packet = $stream->read();
					}
					catch (Exception $e) {
						$logger->info((string) $e);
						continue;
					}

					//	Ignore bad data.
					if (!is_array($packet)) {
//						$logger->info('bad packet');
						continue;
					}

					if ($stream::isMessage($packet)) {
						$packet['created'] = new UTCDateTime();
						$messagesClient->insertOne($packet);
						continue;
					}

					//	Filter. Tweet structure accepts only part of the packet.
					$tweet->assign($packet);

					//	If tweet is not in english then skip it.
					if ($tweet->lang !== 'en') {
						$logger->info('lang not en');
						continue;
					}

					//	Pre calculate tallySet for INSERT_COUNT of tweets.
					$uniqueWords = new Set();
					//	Make sure we have only one of a hashtag per tweet for uniqueHashtags.
					foreach ($tweet->entities->hashtags as $hashtag) {
//						$htext = str_split($hashtag->text);
//						foreach ($htext as $t) {
//							if ($t & chr(0x80)) {
//								continue 2;    //	skip hashtag if it contains a non-ASCII byte
//							}
//						}
						echo $hashtag->text, PHP_EOL;
						$uniqueWords->add($hashtag->text);
						$tallySet->allHashtags->doTally($hashtag->text);
					}

					//	Count unique hashtags for this tweet.
					foreach ($uniqueWords as $uniqueWord) {
						$tallySet->uniqueHashtags->doTally($uniqueWord);
					}

					//	Tally the words in the text.
					$text = strlen($tweet->extended_tweet->full_text) > strlen($tweet->text) ?
						$tweet->extended_tweet->full_text : $tweet->text;

					$text  = preg_replace('#https?://[^ ]+#', '', $text);
					$split = preg_split('/[^a-zA-Z0-9_\']/', $text, null, PREG_SPLIT_NO_EMPTY);
					foreach ($split as $s) {
						if (strlen($s) > 2 && !in_array(strtolower($s), $stopWords, true)) {
							$tallySet->textWords->doTally($s);
						}
					}

					//	Tally user mentions.
					foreach ($tweet->entities->user_mentions as $userMention) {
						$tallySet->userMentions->doTally($userMention->screen_name);
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
					$talliesClient->insertOne($tallySet->toArray(), $insertOptions);
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
			}

			$logger->info('Stopped capturing tweets.');
		}
		catch (\Exception $e) {
			$logger->emergency((string) $e);
		}

		$pidHandler->removeIfExists();
	}

}
