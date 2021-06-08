<?php

namespace Structure\Tweet;

use Diskerror\TypedBSON\TypedClass;

class Retweet extends TypedClass
{
	protected $id = 0;

	use TweetTrait;
}
