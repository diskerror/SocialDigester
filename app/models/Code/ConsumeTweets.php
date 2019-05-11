<?php

namespace Code;

use Diskerror\Typed\ArrayOptions as AO;
use MongoDB\BSON\UTCDateTime;
use Phalcon\Config;
use Phalcon\Di;
use Resource\Messages;
use Resource\Tweets;
use Resource\TwitterClient\Stream;
use Structure\Tweet;

final class ConsumeTweets
{
	const INSERT_COUNT = 90;

	private function __construct() { }

	/**
	 * Open and save a stream of tweets.
	 *
	 * @param Config     $twitterConfig
	 * @param PidHandler $pid_handler
	 *
	 */
	public static function exec(Config $twitterConfig)
	{
		ini_set('memory_limit', 268435456);

		try {
			$stream     = new Stream($twitterConfig->auth);
			$pidHandler = new PidHandler(Di::getDefault()->getConfig()->process);

//			$logger = LoggerFactory::getFileLogger(APP_PATH . '/' . $config->process->name . '.log');
			$logger = LoggerFactory::getStreamLogger();

//			$sh = new StemHandler();

			$tweet = new Tweet();
			$tweet->setArrayOptions(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::SWITCH_ID | AO::TO_BSON_DATE);

			$tweetsClient   = (new Tweets())->getClient();
			$messagesClient = (new Messages())->getClient();


			//	Send request to start a filtered stream.
			$stream->filter([
				'track'          => implode(',', (array)$twitterConfig->track),
				'language'       => 'en',
				'stall_warnings' => true,
			]);

			//	Set PID file to indicate whether we should keep running.
			$pidHandler->setFile();

			//	Announce that we're running.
			$logger->info('Started capturing tweets.');

			while ($pidHandler->exists() && !$stream->isEOF()) {
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

				$tweet->assign($packet);

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

				try {
					//	convert to Mongo compatible object and insert
					$tweetsClient->insertOne($tweet->toArray());
				}
				catch (\Exception $e) {
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
			$logger->emergency((string)$e);
		}

		$pidHandler->removeIfExists();
	}

}
