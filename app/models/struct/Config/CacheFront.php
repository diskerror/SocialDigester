<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 2019-01-31
 * Time: 12:59
 */

namespace Structure\Config;


use Diskerror\Typed\Scalar\TIntegerUnsigned;
use Diskerror\Typed\TypedClass;

class CacheFront extends TypedClass
{
	protected $lifetime = [TIntegerUnsigned::class];
	protected $adapter  = 'data';
}
