<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 2019-01-31
 * Time: 12:58
 */

namespace Structure\Config;


use Diskerror\Typed\TypedClass;

class Cache extends TypedClass
{
	protected $front = [CacheFront::class];
	protected $back  = [CacheBack::class];
}
