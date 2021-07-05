<?php

namespace Structure\Config;


use Diskerror\Typed\TypedClass;

/**
 * Class Process
 *
 * @param $name
 * @param $path
 * @param $procDir
 *
 * @package Structure\Config
 *
 */
class Process extends TypedClass
{
	protected $name    = '';
	protected $path    = '';
	protected $procDir = '';
}
