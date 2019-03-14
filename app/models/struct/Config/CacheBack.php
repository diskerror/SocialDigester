<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 2019-01-31
 * Time: 13:02
 */

namespace Structure\Config;


use Diskerror\Typed\TypedClass;

class CacheBack extends TypedClass
{
	protected $cacheDir = '';
	protected $prefix   = '';
	protected $frontend = null;
	protected $adapter  = 'file';
}
