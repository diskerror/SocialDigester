<?php

namespace Structure\Config;


use Diskerror\Typed\TypedClass;

/**
 * Class Process
 *
 * @property string $name
 * @property string $path
 * @property string $procDir
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
