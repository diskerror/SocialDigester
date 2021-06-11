<?php

namespace Structure\Tweet;

use Diskerror\TypedBSON\TypedClass;
use Structure\Tweet\Entities\Hashtags;
use Structure\Tweet\Entities\Urls;
use Structure\Tweet\Entities\UserMentions;

class Entities extends TypedClass
{
	protected $hashtags      = [Hashtags::class];

//	protected $urls          = [Urls::class];

	protected $user_mentions = [UserMentions::class];

// 	protected $symbols  = '';
// 	protected $polls  = '';
}
