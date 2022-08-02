<?php

namespace Structure;

use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;

/**
 * @property string $name
 * @property string $class
 * @property array  $indexKeys
 */
class CollectionDefinition extends TypedClass
{
	protected $name      = [TString::class];
	protected $class     = [TString::class];
	protected $indexKeys = [TypedArray::class];
}
