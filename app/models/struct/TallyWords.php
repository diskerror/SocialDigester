<?php

namespace Structure;

use Diskerror\TypedBSON\TypedArray;

/**
 * Class TallyWords
 *
 * @package Structure
 */
class TallyWords extends TypedArray
{
	protected $_type = 'float';

	/**
	 * Add word to array.
	 *
	 * @param string $word
	 * @param int $q
	 */
	public function doTally(string $word, int $q = 1)
	{
		if ($this->offsetExists($word)) {
			$this[$word]->set($this[$word]->get() + $q);
		}
		else {
			$this[$word]->set($q);
		}
	}

	/**
	 * Sort array keys by count, descending.
	 */
	public function sort()
	{
		arsort($this->_container);
		return $this;
	}

	public function scaleTally(float $div)
	{
		foreach ($this as &$v) {
			$v = round($v / $div, 2);
		}
	}
}
