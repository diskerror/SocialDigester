<?php

class ConsumeTweets
{
	protected $_mongo;
	protected $_twitterStream;

	/**
	 * @param MongoDB\Client $mongo
	 * @param Twitter\Api\Stream $stream
	 */
	function __construct(MongoDB\Client $mongo, Twitter\Api\Stream $stream)
	{
		$this->_mongo = $mongo;
		$this->_twitterStream = $stream;
	}

	/**
	 * Open and save a stream of tweets.
	 *
	 * @param \Phalcon\Config $track
	 * @param stdClass $logger should be a Logger or Phalcon\Logger\Abstract derivitave
	 * @param PidHandler $pid_handler
	 *
	 */
    public function exec(\Phalcon\Config $track, $logger, $pidHandler)
    {
		try {
			$this->_twitterStream->filter([
				'track' => implode(',', (array)$track),
				'language' => 'en',
				'stall_warnings' => true
			]);
			$pidHandler->setFile();
			$logger->info('Started capturing tweets.');

			while ( !$this->_twitterStream->isEOF() ) {
				if ( !$pidHandler->exists() ) { break; }

				//	get tweet
				$tweet = $this->_twitterStream->readTweet();

				if ( $tweet === null || get_class($tweet) !== 'Twitter\Tweet' ) {
					continue;
				}

				try {
					//	convert to Mongo compatible object and insert
					$this->_mongo->feed->twitter->insertOne( $tweet->getSpecialObj() );
				}
				catch (Exception $e) {
					$m = $e->getMessage();

					if ( preg_match('/Authentication/i', $m) ) {
						$logger->emergency('Mongo ' . $m);
					}
					else {
						if ( preg_match('/duplicate.*key/i', $m) ) {
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
			$logger->emergency((string) $e);
		}

		$pidHandler->removeIfExists();
    }

    public function testAction()
    {
    }

}
