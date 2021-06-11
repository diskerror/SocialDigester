<?php

namespace Structure;

use Diskerror\TypedBSON\TypedClass;
use Structure\Tweet\ExtendedTweet;
use Structure\Tweet\TweetTrait;

/**
 * Class Tweet
 *
 * @package Structure
 *
 * @property $_id
 * @property $created_at
 * @property $contributors
 * @property $entities
 * @property $favorite_count
 * @property $filter_level
 * @property $in_reply_to_screen_name
 * @property $in_reply_to_status_id_str
 * @property $in_reply_to_user_id_str
 * @property $is_quote_status
 * @property $lang
 * @property $place
 * @property $possibly_sensitive
 * @property $retweet_count
 * @property $source
 * @property $text
 * @property $truncated
 * @property $user
 * @property $retweeted_status
 * @property $extended_tweet
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
