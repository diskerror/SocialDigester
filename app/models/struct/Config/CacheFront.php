<?php

namespace Structure\Config;


use Diskerror\Typed\Scalar\TIntegerUnsigned;
use Diskerror\Typed\TypedClass;

/**
 * Class CacheFront
 *
 * @property int $lifetime
 * @property string $adapter
 */
class CacheFront extends TypedClass
{
	protected $lifetime = [TIntegerUnsigned::class];
	protected $adapter  = 'data';
}
