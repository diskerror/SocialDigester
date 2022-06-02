<?php

namespace Resource;

use OAuth;
use Service\Curl;
use Service\CmdBufferReader;
use Structure\Config\TwitterOAuth;
use UnexpectedValueException;

/**
 * Class TwitterV1
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

	private OAuth $_oauth;

	private CmdBufferReader $_streamBuffer;

	/**
	 * Twitter constructor.
	 *
	 * Uses PHP builtin OAuth object.
	 *
	 * @param TwitterOAuth $oauth
	 */
	public function __construct(TwitterOAuth $oauth)
	{
		$this->_oauth = new OAuth($oauth->consumer_key, $oauth->consumer_secret);
		$this->_oauth->setToken($oauth->token, $oauth->token_secret);
	}

	public static function isMessage(array $packet): bool
	{
		if (count(array_intersect_key(self::MESSAGE_KEYS, $packet)) > 0) {
			return true;
		}

		return false;
	}

	/**
	 * Execute Twitter API v1 function.
	 *
	 * Returns an associative array or scalar.
	 *
	 * @param string $requestMethod
	 * @param string $function
	 * @param array  $params
	 *
	 * @return mixed
	 */
	public function exec(string $requestMethod, string $function, array $params = [])
	{
		switch ($function) {
//			case 'statuses/filter':
//			case 'statuses/sample':
//				$this->stream($params);
//				return null;

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

		return self::jsonDecode($curl->exec());
	}

	/**
	 * Start a stream.
	 * https://dev.twitter.com/streaming/overview
	 *
	 * @param array $params
	 *
	 * @return void
	 */
	public function stream(array $params = []): void
	{
		// URL to Twitter stream API v1.
		$url = 'https://stream.twitter.com/1.1/statuses/filter.json';    //	POST method
//		$url = 'https://stream.twitter.com/1.1/statuses/sample.json';    //	GET method

		$data = '';
		if (count($params) > 0) {
			$data = " --data '" . http_build_query($params) . "'";
		}

		$oauthHeader = $this->_oauth->getRequestHeader('POST', $url, $params);

		$this->_streamBuffer = new CmdBufferReader(
			"curl -s -X POST $url$data -H 'Authorization: $oauthHeader' -H 'Accept: application/json'"
		);
	}

	/**
	 * Read from Twitter stream buffer and return an associative array or scalar.
	 *
	 */
	public function getPacket()
	{
		do {
			$raw = $this->_streamBuffer->read();
		} while ($raw == '' && !$this->_streamBuffer->isEOF());

		return self::jsonDecode($raw);
	}

	/**
	 * Returns an associative array or scalar.
	 *
	 */
	public static function jsonDecode(string $in)
	{
		$in = trim($in, "\x00..\x20\x7F");

		switch ($in[0]) {
			case '[':
			case '{':
				break;

			default:
				return $in;
		}

		$packet = json_decode($in, true);
		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new UnexpectedValueException(json_last_error_msg() . "\nInput: \"" . $in . '"');
		}

		return $packet;
	}

	public function streamEOF(): bool
	{
		return $this->_streamBuffer->isEOF();
	}

}
