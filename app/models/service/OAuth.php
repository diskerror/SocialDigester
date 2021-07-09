<?php

namespace Service;

use Structure\Config\OAuth as sOAuth;

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
	 * @param sOAuth $auth
	 */
	public function __construct(sOAuth $auth)
	{
		$this->_secret =
			rawurldecode($auth->consumer_secret) . '&' . rawurldecode($auth->oauth_token_secret);

		$this->_baseOauth = [
			'oauth_consumer_key'     => $auth->consumer_key,
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_token'            => $auth->oauth_token,
			'oauth_version'          => '1.0',
		];
	}

	/**
	 * @param string $url
	 * @param string $method
	 * @param array  $params
	 *
	 * @return string
	 */
	public function getHeader(string $url, string $method, array $params)
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

		$quoted = [];
		uksort($oauth, function($a, $b) { return strcasecmp($a, $b); });
		foreach ($oauth as $k => $v) {
			$quoted[] = rawurlencode($k) . '="' . rawurlencode($v) . '"';
		}

		return 'Authorization: OAuth ' . implode(', ', $quoted);
	}

}
