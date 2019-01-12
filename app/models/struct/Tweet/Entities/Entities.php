<?php

namespace Structure\Tweet\Entities;

use Diskerror\Typed\TypedArray;

class Entities extends \Diskerror\Typed\TypedClass
{
	protected $hashtags      = [TypedArray::class, Hashtags::class];

	protected $urls          = [TypedArray::class, Urls::class];

	protected $user_mentions = [TypedArray::class, UserMentions::class];

// 	protected $symbols  = '';
// 	protected $polls  = '';
}
