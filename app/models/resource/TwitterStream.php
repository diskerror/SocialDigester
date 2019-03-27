<?php

namespace Resource;

use Service\OAuth;
use Structure\Config\Twitter;

/**
 * Class TwitterStream
 *
 * @package Resource
 *
 * @method filter($in=[])
 * @method sample($in=[])
 */
class TwitterStream
{
	protected static $_messageKeys = [
		'control'         => 0,
		'delete'          => 0,
		'disconnect'      => 0,
		'errors'          => 0,
		'event'           => 0,
		'for_user_str'    => 0,
		'for_user'        => 0,
		'friends_str'     => 0,
		'friends'         => 0,
		'limit'           => 0,
		'scrub_geo'       => 0,
		'status_withheld' => 0,
		'user_withheld'   => 0,
		'warning'         => 0,
	];

	protected $_oauth;

	protected $_baseURL;

	/**
	 * @var resource
	 */
	protected $_proc;

	public function __construct(Twitter $config)
	{
		$this->_oauth   = new OAuth($config->oauth);
		$this->_baseURL = $config->url . 'statuses/';
	}

	public static function isMessage(array $packet)
	{
		if (count(array_intersect_key(self::$_messageKeys, $packet)) > 0) {
			return true;
		}

		return false;
	}

	public function __destruct()
	{
		$this->_closeProcIfOpen();
	}

	protected function _closeProcIfOpen()
	{
		if (isset($this->_proc)) {
			pclose($this->_proc);
			unset($this->_proc);
		}
	}

	public function isEOF(): bool
	{
		return feof($this->_proc);
	}

	/**
	 * Get a raw object from the JSON encoded stream.
	 *
	 * @return null|boolean|array
	 */
	public function read()
	{
		$str = fgets($this->_proc, 16384);
		if (false === $str) {
			return null;
		}

		$json = trim($str, "\x00..\x20\x7F");

		return json_decode($json, true);
	}

	/**
	 * Start a stream.
	 * https://dev.twitter.com/streaming/overview
	 *
	 * @return bool
	 * @throws \BadMethodCallException
	 */
	public function __call($function, array $params = []): bool
	{
		switch ($function) {
			case 'filter':
			case 'sample':
				break;

			default:
				throw new \BadMethodCallException();
		}

		$this->_closeProcIfOpen();

		$url = $this->_baseURL . $function . '.json';

		$data = '';
		if (is_array($params[0])) {
			$data = ' --data \'' . http_build_query($params[0]) . '\'';
		}
		elseif (is_string($params[0])) {
			$data = ' --data \'' . rawurlencode($params[0]) . '\'';
		}

		$header = $this->_oauth->getHeader($url, 'GET', $params[0]);
//		cout('curl --get ' . $url . $data . ' --header \'' . $header . '\'' . "\n");die;
		$this->_proc = popen('curl -s --compressed --get ' . $url . $data . ' --header \'' . $header . '\'', 'r');

		return (bool)$this->_proc;
	}

}
