<?php

abstract class TweetTallyAbstract
{
	protected $_twit;
	protected $_tally;

	/**
	 * @param MongoDB\Client $mongo
	 */
	function __construct(MongoDB\Client $mongo)
	{
		$this->_twit = $mongo->feed->twitter;
		$this->_tally = [];
	}

	public static function doTally($word, array &$tally)
	{
		if ( array_key_exists( $word, $tally ) ) {
			++$tally[$word];
		}
		else {
			$tally[$word] = 1;
		}
	}

}
