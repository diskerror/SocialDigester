<?php


namespace Structure\Config;


use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;

/**
 * Class OAuth
 *
 * @property string $consumer_key
 * @property string $consumer_secret
 * @property string $oauth_token
 * @property string $oauth_token_secret
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
