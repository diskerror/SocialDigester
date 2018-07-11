<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/23/18
 * Time: 5:46 PM
 */

class TallyWords implements Iterator
{
	/**
	 * The key will be words and the value will be an integer count of that word.
	 * @var array
	 */
	public $arr;

	public function __construct()
	{
		$this->arr = [];
	}

	public function rewind() : void
	{
		reset($this->arr);
	}

	public function current()
	{
		return current($this->arr);
	}

	public function key() : string
	{
		return key($this->arr);
	}

	public function next() : void
	{
		next($this->arr);
	}

	public function valid() : bool
	{
		return current($this->arr) !== false;
	}

	/**
	 * Add word to array.
	 *
	 * @param string $word
	 */
	public function doTally(string $word)
	{
		if (array_key_exists($word, $this->arr)) {
			++$this->arr[$word];
		}
		else {
			$this->arr[$word] = 1;
		}
	}

	/**
	 * Sort array keys by count, descending.
	 */
	public function sort()
	{
		arsort($this->arr);
	}
}
