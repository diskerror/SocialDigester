<?php

namespace Structure\Config;


use Diskerror\Typed\Scalar\TStringTrim;
use Diskerror\Typed\TypedClass;

/**
 * Class Twitter
 *
 * @param $url
 * @param $auth
 * @param $track
 *
 * @package Structure\Config
 */
class Twitter extends TypedClass
{
	protected $url   = [TStringTrim::class];
	protected $auth  = [OAuth::class];
	protected $track = [WordList::class];
}
