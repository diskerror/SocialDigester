<?php

namespace Structure\Tweet\Entities;

use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;

class Entities extends TypedClass
{
	protected $hashtags = [TypedArray::class, Hashtags::class];

	protected $urls = [TypedArray::class, Urls::class];

	protected $user_mentions = [TypedArray::class, UserMentions::class];

// 	protected $symbols  = '';
// 	protected $polls  = '';
}
