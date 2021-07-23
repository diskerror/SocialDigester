<?php

namespace Structure\Tweet\Entities;

use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;

class Urls extends TypedClass
{
	protected $url = '';

	protected $expanded_url = '';

	protected $display_url = '';

	protected $indices = [TypedArray::class, 'int'];
}
