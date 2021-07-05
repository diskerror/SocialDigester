<?php

namespace Structure\Config;


use Diskerror\Typed\TypedClass;

class CacheBack extends TypedClass
{
	protected $dir      = '';
	protected $prefix   = '';
	protected $frontend = null;
	protected $adapter  = '';
}
