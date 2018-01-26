<?php

class StemHandler
{
	const STEMFILE = APP_PATH . '/stems.txt';
	protected $_stems;

	function __construct()
	{
		if (file_exists(self::STEMFILE)) {
			$this->_stems = json_decode(file_get_contents(self::STEMFILE), true);
		}
		else {
			$this->_stems = [];
		}
	}

	function __destruct()
	{
		file_put_contents(self::STEMFILE, Zend\Json\Json::prettyPrint(json_encode($this->_stems)), LOCK_EX);
	}

	function get($s)
	{
		$s = strtolower($s);
		if (!array_key_exists($s, $this->_stems)) {
			$this->_stems[ $s ] = Diskerror\Stem($s);
		}

		return $this->_stems[ $s ];
	}

}
