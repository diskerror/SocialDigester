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
	 * @param     $word
	 * @param int $q
	 */
	public function doTally($word, float $q = 1): void
	{
		if ($this->offsetExists($word)) {
			$this[$word] += $q;
		} else {
			$this[$word] = $q;
		}
	}

	/**
	 * Sort array keys by count, descending.
	 */
	public function sort(): void
	{
		arsort($this->_container);
	}

	public function scaleTally(float $div): void
	{
		foreach ($this as &$v) {
			$v = round($v / $div, 2);
		}
	}
}
