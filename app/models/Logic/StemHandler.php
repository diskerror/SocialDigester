<?php

namespace Logic;

class StemHandler
{
	const STEM_FILE = APP_PATH . '/stems.txt';

	protected $_stems;

	public function __construct()
	{
		if (file_exists(self::STEM_FILE)) {
			$this->_stems = json_decode(file_get_contents(self::STEM_FILE), true);
		}
		else {
			$this->_stems = [];
		}
	}

	public function __destruct()
	{
		file_put_contents(self::STEM_FILE, json_encode($this->_stems, JSON_PRETTY_PRINT), LOCK_EX);
	}

	public function get($s)
	{
		$s = strtolower($s);
		if (!array_key_exists($s, $this->_stems)) {
			$this->_stems[$s] = \Diskerror\stem($s);
		}

		return $this->_stems[$s];
	}

}
