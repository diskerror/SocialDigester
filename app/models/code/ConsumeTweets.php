<?php

namespace Code;

use MongoDB\BSON\UTCDateTime;
use Resource\Messages;
use Resource\Tweets;
use Resource\TwitterClient\Stream;
use Structure\Tweet;

final class ConsumeTweets
{
	private function __construct() { }

	/**
	 * Open and save a stream of tweets.
	 *
	 * @param \Phalcon\Config  $twitterConfig
	 * @param \Code\PidHandler $pid_handler
	 *
	 */
	public static function exec(\Phalcon\Config $twitterConfig)
	{
		ini_set('memory_limit', 268435456);

		try {
			$stream     = new Stream($twitterConfig->auth);
			$pidHandler = new PidHandler(\Phalcon\Di::getDefault()->getConfig()->process);

//			$logger = LoggerFactory::getFileLogger(APP_PATH . '/' . $config->process->name . '.log');
			$logger = LoggerFactory::getStreamLogger();

//			$sh = new StemHandler();

			$tweet = new Tweet();

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

			while (!$stream->isEOF()) {
				if (!$pidHandler->exists()) {
					break;
				}

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
					$packet['created'] = new UTCDateTime((int)(microtime(true)*1000));
					$messagesClient->insertOne($packet);
					continue;
				}

				$tweet->assign($packet);


				//	remove URLs from text
// 				$text = preg_replace('#https?:[^ ]+#', '', $tweet->text);
// 				$words = [];
//
// 				//	build the two stem lists
// 				$split = preg_split('/[^a-zA-Z0-9]/', $text, null, PREG_SPLIT_NO_EMPTY);
// 				foreach( $split as $s ) {
// 					$words[] = $sh->get( $s );
// 				}

				//	build stem pairs
// 				$last = '';
// 				foreach ( $tweet->words as $w ) {
// 					$tweet->pairs[] = $last . $w;
// 					$last = $w;
// 				}
// 				$tweet->pairs[] = $last;


				try {
					$tweetsClient->insertOne($tweet);
				}
				catch (\Exception $e) {
					$m = $e->getMessage();

					if (preg_match('/Authentication/i', $m)) {
						$logger->emergency('Mongo ' . $m);
					}
					else {
						if (preg_match('/duplicate.*key/i', $m)) {
							$logger->warning('dup');
						}
						else {
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
