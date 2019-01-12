<?php

namespace Structure;

use Diskerror\Typed\TypedClass;
use Structure\Tweet\Retweet;
use Structure\Tweet\TweetTrait;

class Tweet extends TypedClass
{
	protected $_map = [
		'id'  => '_id',    //	from Twitter
	];

	/**
	 * Only this top level "id" is used for the MongoDb "_id" auto index.
	 */
	protected $_id = 0;

	use TweetTrait;

	protected $retweeted_status = [Retweet::class];

}
