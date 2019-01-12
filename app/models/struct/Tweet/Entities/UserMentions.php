<?php

namespace Structure\Tweet\Entities;

use Diskerror\Typed\TypedArray;

class UserMentions extends \Diskerror\Typed\TypedClass
{
	protected $id          = '';

	protected $screen_name = '';

	protected $name        = '';

	protected $indices     = [TypedArray::class, 'int'];

}
