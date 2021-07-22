<?php

namespace Resource\TwitterClient;

use Phalcon\Config;
use Structure\Config\OAuth;

abstract class ClientAbstract
{
	/**
	 * @var Config
	 */
	protected $_auth;

	/**
	 * @var array
	 */
	protected $_baseOauth;

	/**
	 * @var string
	 */
	protected $_baseURL;


	/**
	 * ClientAbstract constructor.
	 *
	 * @param OAuth $auth
	 */
	public function __construct(OAuth $auth)
	{
		$this->_auth    = $auth;
		$this->_baseURL = 'https://stream.twitter.com/1.1/';

		$this->_baseOauth = [
			'oauth_consumer_key'     => $this->_auth->consumer_key,
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_token'            => $this->_auth->oauth_token,
			'oauth_version'          => '1.0',
		];
	}

	protected function _getHeader($url, $method, array $params)
	{
		$oauth = $this->_baseOauth + [
				'oauth_nonce'     => base64_encode(md5(microtime(true), true)),
				'oauth_timestamp' => time(),
			];

		$params += $oauth;
		$rawEncoded = [];
		uksort($params, function($a, $b) { return strcasecmp($a, $b); });
		foreach ($params as $k => $v) {
			$rawEncoded[] = rawurlencode($k) . '=' . rawurlencode($v);
		}

		$oauth['oauth_signature'] = base64_encode(hash_hmac(
			'sha1',
			$method . "&" . rawurlencode($url) . '&' . rawurlencode(implode('&', $rawEncoded)),
			rawurlencode($this->_auth->consumer_secret) . '&' . rawurlencode($this->_auth->oauth_token_secret),
			true
		));

		$quoted = [];
		uksort($oauth, function($a, $b) { return strcasecmp($a, $b); });
		foreach ($oauth as $k => $v) {
			$quoted[] = rawurlencode($k) . '="' . rawurlencode($v) . '"';
		}

		return 'Authorization: OAuth ' . implode(', ', $quoted);
		//	This is friggin' convoluted.
	}

}
