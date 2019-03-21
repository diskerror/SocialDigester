<?php

namespace Resource;

class StemHandler
{
	protected $_stemFileName;

	protected $_stems;

	public function __construct(string $fileName)
	{
		$this->_stemFileName = $fileName;

		if (file_exists($this->_stemFileName)) {
			$this->_stems = json_decode(file_get_contents($this->_stemFileName), true);
		}
		else {
			$this->_stems = [];
		}
	}

	public function __destruct()
	{
		file_put_contents($this->_stemFileName, json_encode($this->_stems, JSON_PRETTY_PRINT), LOCK_EX);
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
