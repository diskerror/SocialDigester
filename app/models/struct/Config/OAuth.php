<?php


namespace Structure\Config;


use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;

/**
 * Class OAuth
 *
 * @param string $consumer_key
 * @param string $consumer_secret
 * @param string $oauth_token
 * @param string $oauth_token_secret
 *
 * @package Structure\Config
 */
class OAuth extends TypedClass
{
	protected $consumer_key       = [TString::class];
	protected $consumer_secret    = [TString::class];
	protected $oauth_token        = [TString::class];
	protected $oauth_token_secret = [TString::class];
}
