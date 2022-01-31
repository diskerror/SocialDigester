<?php

namespace Structure;

use Diskerror\TypedBSON\TypedClass;
use Structure\Tweet\ExtendedTweet;
use Structure\Tweet\Retweet;
use Structure\Tweet\TweetTrait;

/**
 * Class Tweet
 *
 * @package Structure
 *
 * @property Retweet $retweeted_status
 * @property ExtendedTweet $extended_tweet
 */
class Tweet extends TypedClass
{
	protected $_map = [
		'id_str' => '_id',    //	from Twitter
	];
	/**
	 * Only this top level "id" is used for the MongoDb "_id" index.
	 */
	protected $_id = '';

	use TweetTrait;

	protected $retweeted_status = [Retweet::class];

	protected $extended_tweet = [ExtendedTweet::class];

}
