<?php

namespace Structure\Tweet\Entities;

use Diskerror\Typed\TypedArray;

class Hashtags extends \Diskerror\Typed\TypedClass
{
	protected $text    = '';

	protected $indices = [TypedArray::class, 'int'];
}
