<?php

class ConsumeTweets
{
	protected $_mongo;
	protected $_twitterStream;

	/**
	 * @param MongoDB\Client       $mongo
	 * @param TwitterClient\Stream $stream
	 */
	function __construct(MongoDB\Client $mongo, TwitterClient\Stream $stream)
	{
		$this->_mongo = $mongo;
		$this->_twitterStream = $stream;
	}

	/**
	 * Open and save a stream of tweets.
	 *
	 * @param \Phalcon\Config $track
	 * @param stdClass        $logger should be a Logger or Phalcon\Logger\Abstract derivitave
	 * @param PidHandler      $pid_handler
	 *
	 */
	public function exec(\Phalcon\Config $track, $logger, $pidHandler)
	{
		try {
			$this->_twitterStream->filter([
				'track'          => implode(',', (array)$track),
				'language'       => 'en',
				'stall_warnings' => true,
			]);
			$pidHandler->setFile();
			$logger->info('Started capturing tweets.');
			$sh = new StemHandler();

			while (!$this->_twitterStream->isEOF()) {
				if (!$pidHandler->exists()) {
					break;
				}

				//	get tweet
				try {
					$packet = $this->_twitterStream->read();

					if (!is_object($packet)) {
						continue;
					}

					if ($this->_twitterStream::isMessage($packet)) {
						if ($logger !== null) {
							$logger->info(json_encode($packet));
						}

						continue;
					}

					$tweet = new \Tweet\Tweet($packet);
				}
				catch (Exception $e) {
					if ($logger !== null) {
						$logger->info((string)$e);
					}
					continue;
				}


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
					//	convert to Mongo compatible object and insert
					$this->_mongo->feed->twitter->insertOne($tweet->getSpecialObj());
				}
				catch (Exception $e) {
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
		catch (Exception $e) {
			$logger->emergency((string)$e);
		}

		$pidHandler->removeIfExists();
	}

	public function testAction()
	{
	}

}
