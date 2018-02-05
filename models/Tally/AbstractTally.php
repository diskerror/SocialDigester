<?php

namespace Tally;

abstract class AbstractTally
{
	protected $_tweets;

	private $_tally;

	/**
	 * @param \MongoDB\Collection $tweets
	 */
	function __construct(\MongoDB\Collection $tweets)
	{
		$this->_tweets = $tweets;
		$this->_tally = [];
	}

	protected function doTally($word)
	{
		if (array_key_exists($word, $this->_tally)) {
			++$this->_tally[$word];
		}
		else {
			$this->_tally[$word] = 1;
		}
	}

	protected function _rSortTally()
	{
		arsort($this->_tally);
	}

	protected function &_getTally()
	{
		return $this->_tally;
	}
}
