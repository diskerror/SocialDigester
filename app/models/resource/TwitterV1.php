<?php

namespace Resource;

use Service\Curl;
use Service\CmdReadBuffer;
use Service\StdIo;
use Structure\Config\OAuth as cOAuth;

/**
 * Class TwitterStream
 *
 * @package Resource
 *
 * @method filter($in = [])
 * @method sample($in = [])
 */
class TwitterV1
{
	protected const MESSAGE_KEYS = [
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

	/**
	 * Twitter constructor.
	 *
	 * @param \OAuth $oauth
	 */
	public function __construct(cOAuth $oauth)
	{
//		$this->_oauth = new Service\OAuth($oauth);
		$this->_oauth = new \OAuth($oauth->consumer_key, $oauth->consumer_secret);
		$this->_oauth->setToken($oauth->token, $oauth->token_secret);
	}

	public static function isMessage(array $packet)
	{
		if (count(array_intersect_key(self::MESSAGE_KEYS, $packet)) > 0) {
			return true;
		}

		return false;
	}

	/**
	 * Start a stream.
	 * https://dev.twitter.com/streaming/overview
	 *
	 * @param string $requestMethod
	 * @param string $function
	 * @param array $params
	 */
	public function exec($requestMethod, $function, array $params = [])
	{
		switch ($function) {
			case 'statuses/filter':
			case 'statuses/sample':
				return $this->stream($params);

			case 'statuses/oembed':
				$url = 'https://publish.twitter.com/oembed.json';
				break;

			default:
				$url = 'https://api.twitter.com/1.1/' . $function . '.json';
		}

		$opts = [
			CURLOPT_SSH_COMPRESSION => true,
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_HTTPHEADER      => [
				'Authorization: ' . $this->_oauth->getRequestHeader($requestMethod, $url, $params),
				'Accept: application/json',
			],
		];

		switch ($requestMethod) {
			case 'GET':
				$opts[CURLOPT_HTTPGET] = true;
				$url                   .= '?' . http_build_query($params);
				break;

			default:
				$opts[CURLOPT_CUSTOMREQUEST] = $requestMethod;
				$opts[CURLOPT_POSTFIELDS]    = $params;
		}

		$curl = new Curl($url, $opts);

		return $curl->exec();
	}

	/**
	 * Start a stream.
	 * https://dev.twitter.com/streaming/overview
	 *
	 * @return bool
	 */
	public function stream(array $params = []): CmdReadBuffer
	{
		// URL to Twitter stream API v1.
		$url = 'https://stream.twitter.com/1.1/statuses/filter.json';    //	POST method
//		$url = 'https://stream.twitter.com/1.1/statuses/sample.json';    //	GET method

		$data = '';
		if (count($params) > 0) {
			$data = " --data '" . http_build_query($params) . "'";
		}

		$oauthHeader = $this->_oauth->getRequestHeader('POST', $url, $params);

		return new CmdReadBuffer(
			"curl -s -X POST $url$data -H 'Authorization: $oauthHeader' -H 'Accept: application/json'");
	}

}
