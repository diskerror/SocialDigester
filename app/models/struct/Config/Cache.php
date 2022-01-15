<?php

namespace Structure\Config;


use Diskerror\Typed\TypedClass;

/**
 * Class Cache
 *
 * @property $front
 * @property $back
 */
class Cache extends TypedClass
{
	protected $front = [CacheFront::class];
	protected $back  = [CacheBack::class];
}
