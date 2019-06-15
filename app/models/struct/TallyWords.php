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
	 */
	public function doTally(string $word)
	{
		if ($this->offsetExists($word)) {
			++$this[$word];
		}
		else {
			$this[$word] = 1;
		}
	}

	/**
	 * Sort array keys by count, descending.
	 */
	public function sort()
	{
		arsort($this->_container);
	}

	public function scaleTally(float $div)
	{
		foreach ($this as &$v) {
			$v = round($v / $div, 2);
		}
	}
}
