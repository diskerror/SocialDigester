<?php

namespace Code;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;
use Resource\LoggerFactory;
use Resource\PidHandler;
use Resource\TwitterStream;
use Structure\Config;
use Structure\Tweet;

final class ConsumeTweets
{
	private function __construct() { }

	/**
	 * Open and save a stream of tweets.
	 *
	 * @param TwitterStream  $stream
	 * @param Config\Twitter $twitterConfig
	 * @param PidHandler     $pidHandler
	 * @param LoggerFactory  $logger
	 * @param Collection     $tweetsClient
	 * @param Collection     $messagesClient
	 */
	public static function exec(
		TwitterStream $stream,
		Config\Twitter $twitterConfig,
		PidHandler $pidHandler,
		LoggerFactory $logger,
		Collection $tweetsClient,
		Collection $messagesClient
	)
	{
		ini_set('memory_limit', 268435456);

		try {
//			$sh = new StemHandler();

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

			$tweet = new Tweet();
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
					$packet['created'] = new UTCDateTime((int)(microtime(true) * 1000));
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
