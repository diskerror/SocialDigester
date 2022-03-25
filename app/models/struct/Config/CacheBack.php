<?php

namespace Structure\Config;


use Diskerror\Typed\TypedClass;

/**
 * Class CacheBack
 *
 * @property string $dir
 * @property string $prefix
 * @property string $frontend
 * @property string $adapter
 */
class CacheBack extends TypedClass
{
	protected $dir      = '';
	protected $prefix   = '';
	protected $frontend = '';
	protected $adapter  = '';
}
