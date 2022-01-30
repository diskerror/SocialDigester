<?php

namespace Service;

use Service\Exception\RuntimeException;
use const PHP_EOL;

class Curl
{
	protected $_curl = null;

	public function __construct($url = null, array $opt = [])
	{
		$this->_curl = curl_init($url);
		$this->_errorCheck();
		if (count($opt)) {
			$this->setopt_array($opt);
		}
	}

	public function __destruct()
	{
		if (isset($this->_curl)) {
			curl_close($this->_curl);
			unset($this->_curl);
		}
	}

	public function setopt($opt, $val = true)
	{
		curl_setopt($this->_curl, $opt, $val);
		$this->_errorCheck();
	}

	public function setopt_array(array $opta)
	{
		curl_setopt_array($this->_curl, $opta);
		$this->_errorCheck();
	}

	public function exec()
	{
		$r = curl_exec($this->_curl);
		$this->_errorCheck();
		return $r;
	}

	protected function strerror()
	{
		if (isset($this->_curl)) {
			$err_num = $this->errno();
			return curl_strerror($err_num) . ' ' . $err_num . PHP_EOL;
		}
	}

	protected function _errorCheck()
	{
		if (isset($this->_curl) && ($err_num = $this->errno()) > 0) {
			throw new RuntimeException($this->error(), $err_num);
		}
	}

	public function errno()
	{
		return curl_errno($this->_curl);
	}

	public function error()
	{
		return curl_error($this->_curl);
	}

	public static function version()
	{
		return curl_version();
	}
}
