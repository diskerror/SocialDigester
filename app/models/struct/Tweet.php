<?php

namespace Structure;

use Diskerror\TypedBSON\TypedClass;
use Structure\Tweet\Created;
use Structure\Tweet\Entities;
use Structure\Tweet\ExtendedTweet;
use Structure\Tweet\TweetTrait;

/**
 * Class Tweet
 *
 * @package Structure
 *
 * @property Created $created_at
 * @property $contributors
 * @property Entities $entities
 * @property $favorite_count
 * @property $filter_level
 * @property $in_reply_to_screen_name
 * @property $in_reply_to_status_id_str
 * @property $in_reply_to_user_id_str
 * @property $is_quote_status
 * @property string $lang
 * @property $place
 * @property $possibly_sensitive
 * @property $retweet_count
 * @property $source
 * @property string $text
 * @property $truncated
 * @property $user
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
//	protected $retweeted_status = [Retweet::class];

	use TweetTrait;

	protected $extended_tweet = [ExtendedTweet::class];

}
