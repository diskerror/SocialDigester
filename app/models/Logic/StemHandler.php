<?php

namespace Logic;

class StemHandler
{
	const STEMFILE = APP_PATH . '/stems.txt';

	protected $_stems;

	public function __construct()
	{
		if (file_exists(self::STEMFILE)) {
			$this->_stems = json_decode(file_get_contents(self::STEMFILE), true);
		}
		else {
			$this->_stems = [];
		}
	}

	public function __destruct()
	{
		file_put_contents(self::STEMFILE, json_encode($this->_stems, JSON_PRETTY_PRINT), LOCK_EX);
	}

	public function get($s)
	{
		$s = strtolower($s);
		if (!array_key_exists($s, $this->_stems)) {
			$this->_stems[$s] = Diskerror\stem($s);
		}

		return $this->_stems[$s];
	}

}
