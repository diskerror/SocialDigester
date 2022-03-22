<?php
/** @noinspection SpellCheckingInspection */

namespace Service;

/**
 * Class CurlBuffer
 *
 * @package Service
 *
 */
class CmdBufferReader
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

}
