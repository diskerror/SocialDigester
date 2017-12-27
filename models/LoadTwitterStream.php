<?php

class LoadTwitterStream
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
// 		$sh = new StemHandler;

		try {
			$this->_twitterStream->filter([
				'track'=>implode(',', (array)$track),
				'language'=>'en',
				'stall_warnings'=>true
			]);
			$pidHandler->setFile();
			$logger->info('Started capturing tweets.');

			while( !$this->_twitterStream->isEOF() ) {
				if ( !$pidHandler->exists() ) { break; }

				//	get tweet
				try {
					$twitterPacket = $this->_twitterStream->read();
					if ( !((boolean)$twitterPacket) ) {
						continue;
					}
				}
				catch (Exception $e) {
					$logger->info((string) $e);
					continue;
				}

// file_put_contents('messages.txt', print_r($twitterPacket, true)."\n\n", FILE_APPEND);continue;

				if ( Twitter\Api\Stream::_isMessage($twitterPacket) ) {
// 					$logger->info(json_encode($twitterPacket));
					continue;
				}

				//	Reject non-english.
// 				if ( property_exists($twitterPacket, 'lang') && $twitterPacket->lang !== 'en' ) {
// 					continue;
// 				}

				//	filter tweet structure
				$tweet = new Twitter\Tweet( $twitterPacket );

				//	copy up hashtag text
				if ( isset($tweet->entities) && isset($tweet->entities->hashtags) ) {
					foreach ( $tweet->entities->hashtags as $h ) {
						if ( strlen($h->text) > 1 ) {
							$tweet->hashtags[] = $h->text;
						}
					}
				}

// file_put_contents('messages.txt', print_r($tweet->toArray(), true)."\n\n", FILE_APPEND);continue;

				//	remove URLs from text
// 				$text = preg_replace('#https?:[^ ]+#', '', $tweet->text);
//
// 				//	build the two stem lists
// 				$split = preg_split('/[^a-zA-Z0-9]/', $text, null, PREG_SPLIT_NO_EMPTY);
// 				foreach( $split as $s ) {
// 					$tweet->words[] = $sh->get( $s );
// 				}
//
// 				//	build stem pairs
// 				$last = '';
// 				foreach ( $tweet->words as $w ) {
// 					$tweet->pairs[] = $last . $w;
// 					$last = $w;
// 				}
// 				$tweet->pairs[] = $last;

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
// 							$logger->warning('dup');
						}
						else {
							$logger->warning('Mongo ' . $m);
						}
					}
				}

// 				cout( Zend\Json\Json::prettyPrint(json_encode( $tsr )) . PHP_EOL );

// 				$t = preg_replace('/\s+/', ' ', $t->text);
// 				cout( $t->user->screen_name . ' -> ' . $t->text . PHP_EOL );
			}

			$logger->info('Stopped capturing tweets.');
		}
		catch (Exception $e) {
			$logger->emergency((string) $e);
		}
    }

    public function testAction()
    {
    }

}
