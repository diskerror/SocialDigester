<?php

namespace Twitter\Api;

use Twitter\Tweet;

class Stream extends ApiAbstract
{
    /**
     * @var resource
     */
    protected $_proc;

	public function __construct(\Phalcon\Config $auth)
	{
		parent::__construct($auth);
		$this->_baseURL .= 'statuses/';
	}

	public function __destruct()
	{
		$this->_closeProcIfOpen();
	}

	protected function _closeProcIfOpen()
	{
		if ( isset($this->_proc) ) {
			pclose($this->_proc);
			unset($this->_proc);
		}
	}

	public function isEOF()
	{
		return feof($this->_proc);
	}

	/**
	 * Get a raw object from the JSON encoded stream.
	 *
	 * @return null|boolean|stdClass
	 */
	public function read()
	{
		$json = trim( fgets($this->_proc, 16384), "\x00..\x20" );

		return json_decode( $json );
	}

	/**
	 * Get a Tweet structure from the stream.
	 *
	 * @param stdClass|null $logger should be a Logger or Phalcon\Logger\Abstract derivitave
	 * @return null|Twitter\Tweet
	 */
	public function readTweet($logger=null)
	{
		try {
			$packet = $this->read();

			if ( !is_object($packet) ) {
				return null;
			}

			if ( self::isMessage($packet) ) {
				if ( $logger !== null ) {
					$logger->info($packet->getSpecialObj(['dateToBsonDate'=>false]));
				}

// 				file_put_contents('messages.txt', print_r($packet->getSpecialObj(['dateToBsonDate'=>false]), true)."\n\n", FILE_APPEND);

				return null;
			}
		}
		catch (Exception $e) {
			if ( $logger !== null ) {
				$logger->info((string) $e);
			}
			return null;
		}

		return new Tweet($packet);
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
			break;

			default:
			throw new LogicException('bad function name');
		}

		$this->_closeProcIfOpen();

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
		$this->_proc = popen( 'curl -s --compressed --get ' . $url . $data . ' --header \'' . $header . '\'', 'r' );

		return (bool) $this->_proc;
	}

	public static function isMessage(\stdClass $packet)
	{
		if (
			property_exists($packet, 'control') ||
			property_exists($packet, 'delete') ||
			property_exists($packet, 'disconnect') ||
			property_exists($packet, 'errors') ||
			property_exists($packet, 'event') ||
			property_exists($packet, 'for_user_str') ||
			property_exists($packet, 'for_user') ||
			property_exists($packet, 'friends_str') ||
			property_exists($packet, 'friends') ||
			property_exists($packet, 'limit') ||
			property_exists($packet, 'scrub_geo') ||
			property_exists($packet, 'status_withheld') ||
			property_exists($packet, 'user_withheld') ||
			property_exists($packet, 'warning')
			) {
			return true;
		}

		return false;
	}

}
