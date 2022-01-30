<?php

namespace Service;

/**
 * Class CurlBuffer
 *
 * @package Service
 *
 */
class CmdReadBuffer
{
	private const MAX_READ = 65536;

	/**
	 * @var resource
	 */
	private $_res;

	public function __construct(string $cmd)
	{
//		StdIo::outln($cmd);die;
		$this->_res = popen($cmd, 'r');
	}

	public function __destruct()
	{
		if (isset($this->_res)) {
			pclose($this->_res);
			unset($this->_res);
		}
	}

	public function isEOF(): bool
	{
		return feof($this->_res);
	}

	public function read(): string
	{
		return fgets($this->_res, self::MAX_READ);
	}

	/**
	 *
	 * @return null|boolean|array
	 */
	public function readJson()
	{
		$str = $this->read();
		if (false === $str) {
			return false;
		}

		$str = trim($str, "\x00..\x20\x7F");

		return json_decode($str, true);
	}

}
