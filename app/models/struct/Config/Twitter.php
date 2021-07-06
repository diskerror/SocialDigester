<?php

namespace Structure\Config;


use Diskerror\Typed\TypedClass;

/**
 * Class Twitter
 *
 * @param $auth
 * @param $track
 *
 * @package Structure\Config
 */
class Twitter extends TypedClass
{
	protected $auth  = [TwitterAuth::class];
	protected $track = [WordList::class];
}
