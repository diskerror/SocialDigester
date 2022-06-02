<?php

namespace Service;

use Structure\Config\TwitterOAuth;

/**
 * Class OAuth
 *
 * @package Service
 */
class OAuth
{
	/**
	 * @var string
	 */
	protected $_secret;

	/**
	 * @var array
	 */
	protected $_baseOauth;

	/**
	 * OAuth constructor.
	 *
	 * @param TwitterOAuth $config
	 */
	public function __construct(TwitterOAuth $config)
	{
		$this->_secret =
			rawurldecode($config->consumer_secret) . '&' . rawurldecode($config->token_secret);

		$this->_baseOauth = [
			'oauth_consumer_key'     => $config->consumer_key,
			'oauth_signature_method' => 'HMAC-SHA1',
			'token'                  => $config->token,
			'oauth_version'          => '1.0',
		];
	}

	/**
	 * @param string $method
	 * @param string $url
	 * @param array  $params
	 *
	 * @return string
	 */
	public function getHeader(string $method, string $url, array $params): string
	{
		$oauth = $this->_baseOauth;

		$oauth['oauth_nonce']     = base64_encode(md5(microtime(true), true));
		$oauth['oauth_timestamp'] = time();

		$params     += $oauth;
		$rawEncoded = [];
		uksort($params, function($a, $b) { return strcasecmp($a, $b); });
		foreach ($params as $k => $v) {
			$rawEncoded[] = rawurlencode($k) . '=' . rawurlencode($v);
		}

		$oauth['oauth_signature'] = base64_encode(hash_hmac(
			'sha1',
			$method . '&' . rawurlencode($url) . '&' . rawurlencode(implode('&', $rawEncoded)),
			$this->_secret,
			true
		));

		uksort($oauth, function($a, $b) { return strcasecmp($a, $b); });

		$quoted = [];
		foreach ($oauth as $k => $v) {
			$quoted[] = rawurlencode($k) . '="' . rawurlencode($v) . '"';
		}

		return 'Authorization: OAuth ' . implode(', ', $quoted);
	}

}
