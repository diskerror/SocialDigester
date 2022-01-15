<?php

namespace Structure\Config;


use Diskerror\Typed\Scalar\TStringTrim;
use Diskerror\Typed\TypedClass;

/**
 * Class Twitter
 *
 * @property string $url
 * @property OAuth $auth
 * @property WordList $track
 *
 * @package Structure\Config
 */
class Twitter extends TypedClass
{
	protected $url   = [TStringTrim::class];
	protected $auth  = [OAuth::class];
	protected $track = [WordList::class];
}
