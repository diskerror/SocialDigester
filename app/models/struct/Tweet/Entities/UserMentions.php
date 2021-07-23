<?php

namespace Structure\Tweet\Entities;

use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;

class UserMentions extends TypedClass
{
	protected $id = '';

	protected $screen_name = '';

	protected $name = '';

	protected $indices = [TypedArray::class, 'int'];

}
