<?php

namespace Structure\Tweet;

use Diskerror\Typed\TypedClass;

class Retweet extends TypedClass
{
	protected $id = 0;

	use TweetTrait;
}
