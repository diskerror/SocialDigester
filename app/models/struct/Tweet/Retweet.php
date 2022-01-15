<?php

namespace Structure\Tweet;

use Diskerror\TypedBSON\TypedClass;

/**
 * @property string $id
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
 */
class Retweet extends TypedClass
{
	protected $id = '';

	use TweetTrait;
}
