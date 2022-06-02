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
	 * @param float  $q
	 */
	public function doTally(string $word, float $q = 1): void
	{
		$this->_doTally($q, $word);
	}

	/**
	 * Add list of words to array.
	 *
	 * @param array $in
	 */
	public function countArrayValues(array $in): void
	{
		$counted = array_count_values($in);
		array_walk($counted, [$this, '_doTally']);
	}

	protected function _doTally(float $q, string $word): void
	{
		if ($word !== '') {
			if ($this->offsetExists($word)) {
				$this[$word] += $q;
			}
			else {
				$this[$word] = $q;
			}
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
