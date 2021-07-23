<?php

namespace Structure\Tweet\Entities;

use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;

class Hashtags extends TypedClass
{
	protected $text = '';

	protected $indices = [TypedArray::class, 'int'];
}
