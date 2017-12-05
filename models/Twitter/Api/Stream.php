<?php

namespace Twitter\Api;

class Stream extends ApiAbstract
{
    /**
     * @var resource
     */
    protected $_fp;

	public function __construct(\Phalcon\Config $auth)
	{
		parent::__construct($auth);
		$this->_baseURL .= 'statuses/';
	}

	public function __destruct()
	{
		$this->_initFp();
	}

	protected function _initFp()
	{
		if ( isset($this->_fp) ) {
			fclose($this->_fp);
			unset($this->_fp);
		}
	}

	public function isEOF()
	{
		return feof($this->_fp);
	}

	/**
	 * Read a drop from the stream.
	 */
	public function read()
	{
		$drop = trim( fgets($this->_fp, 16384) );
// 		return $drop . "\n\n";

		if ( !isset($drop[0]) || $drop[0] !== '{' ) {
			return false;
		}

		return json_decode( $drop );
	}

	/**
	 * Start a stream.
	 * https://dev.twitter.com/streaming/overview
	 *
	 * @return boolean
	 */
	public function __call($function, array $params = [])
	{
		switch ( $function ) {
			case 'filter':
			case 'sample':
			case 'firehose':	//	untested
			break;

			default:
			throw new LogicException('bad function name');
		}

		$this->_initFp();

		$url = $this->_baseURL . $function . '.json';

		$data = '';
		if ( is_array($params[0]) ) {
			$data = ' --data \'' . http_build_query($params[0]) . '\'';
		}
		elseif ( is_string($params[0]) ) {
			$data = ' --data \'' . rawurlencode($params[0]) . '\'';
		}

		$header = $this->_getHeader($url, 'GET', $params[0]);
// 		cout('curl --get ' . $url . $data . ' --header \'' . $header . '\'' . "\n");die;
		$this->_fp = expect_popen( 'curl --get ' . $url . $data . ' --header \'' . $header . '\'' );

		return (bool) $this->_fp;
	}

	public static function _isMessage(\stdClass $tweet)
	{
		if (
			property_exists($tweet, 'control') ||
			property_exists($tweet, 'delete') ||
			property_exists($tweet, 'disconnect') ||
			property_exists($tweet, 'errors') ||
			property_exists($tweet, 'event') ||
			property_exists($tweet, 'for_user_str') ||
			property_exists($tweet, 'for_user') ||
			property_exists($tweet, 'friends_str') ||
			property_exists($tweet, 'friends') ||
			property_exists($tweet, 'limit') ||
			property_exists($tweet, 'scrub_geo') ||
			property_exists($tweet, 'status_withheld') ||
			property_exists($tweet, 'user_withheld') ||
			property_exists($tweet, 'warning')
			) {
			return true;
		}

		return false;
	}

}
