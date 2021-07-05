<?php

namespace Structure\Tweet\Entities;

use Diskerror\TypedBSON\TypedArray;

class UserMentions extends TypedArray
{
	protected $_type = UserMention::class;
}
