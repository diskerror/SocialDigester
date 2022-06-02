<?php

namespace Structure\Tweet\Entities;

use Diskerror\Typed\Scalar\TStringNormalize;
use Diskerror\TypedBSON\TypedClass;

/**
 * @property string $text
 * @property array  $indices
 */
class Hashtag extends TypedClass
{
	protected $text = [TStringNormalize::class];

//	protected $indices = [TypedArray::class, 'int'];
}
