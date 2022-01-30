<?php


namespace Structure\Config;


use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;

/**
 * Class OAuth
 *
 * @property string $consumer_key
 * @property string $consumer_secret
 * @property string $token
 * @property string $token_secret
 *
 * @package Structure\Config
 */
class OAuth extends TypedClass
{
	protected $consumer_key    = [TString::class];
	protected $consumer_secret = [TString::class];
	protected $token           = [TString::class];
	protected $token_secret    = [TString::class];
}
