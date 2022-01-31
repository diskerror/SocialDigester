<?php

namespace Structure\Tweet;

use Diskerror\TypedBSON\TypedClass;

/**
 * @property string $id
 *
 */
class Retweet extends TypedClass
{
	protected $id = '';

	use TweetTrait;
}
