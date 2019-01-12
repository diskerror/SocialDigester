<?php

namespace Structure\Tweet\Entities;

use Diskerror\Typed\TypedArray;

class Urls extends \Diskerror\Typed\TypedClass
{
	protected $url          = '';

	protected $expanded_url = '';

	protected $display_url  = '';

	protected $indices      = [TypedArray::class, 'int'];
}
