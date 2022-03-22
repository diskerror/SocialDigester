<?php

namespace Structure\Config;


use Diskerror\Typed\Scalar\TStringTrim;
use Diskerror\Typed\TypedClass;

/**
 * Class Twitter
 *
 * @property string $url
 * @property OAuth $auth
 *
 * @package Structure\Config
 */
class Twitter extends TypedClass
{
	protected $url  = [TStringTrim::class, 'https://stream.twitter.com/1.1/'];
	protected $auth = [OAuth::class];
}
