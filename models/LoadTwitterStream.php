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
	 * @param string $processLabel
	 *
	 */
    public function filter(\Phalcon\Config $track, $logger, $processLabel='tweets')
    {
    	$pid = new PidHandler($processLabel);
		$stream = $this->_twitterStream;
		$twit = $this->_mongo->feed->twitter;
// 		$sh = new StemHandler;

		try {
			$stream->filter(['track'=>implode(',', (array)$track),'language'=>'en','stall_warnings'=>true]);
			$logger->info('Started capturing tweets.');

			while( !$stream->isEOF() ) {
				if ( !$pid->exists() ) { break; }

				//	get tweet
				try {
					$raw = $stream->read();
					if ( !((boolean)$raw) ) {
						continue;
					}
				}
				catch (Exception $e) {
					$logger->info((string) $e);
					continue;
				}

				if ( $stream::_isMessage($raw) ) {
					$logger->info(json_encode($raw));
					continue;
				}

				//	Reject non-english.
// 				if ( property_exists($raw, 'lang') && $raw->lang !== 'en' ) {
// 					continue;
// 				}

				//	filter tweet structure
				$tweet = new Twitter\Tweet\Tweet( $raw );

				//	copy up hashtag text
				if ( isset($tweet->entities) && isset($tweet->entities->hashtags) ) {
					foreach ( $tweet->entities->hashtags as $h ) {
						if ( strlen($h->text) > 1 ) {
							$tweet->hashtags[] = $h->text;
						}
					}
				}

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
					$twit->insertOne( $tweet->getSpecialObj() );
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
