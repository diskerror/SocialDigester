<?php

namespace Structure\Tweet;

use Diskerror\Typed\Scalar\TStringNormalize;

/**
 * Trait TweetTrait
 *
 * @package Structure\Tweet
 *
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
 */
trait TweetTrait
{
	protected $created_at                = [Created::class];

//	protected $contributors              = null;

	protected $entities                  = [Entities::class];

//	protected $extended_entities         = [ExtendedEntities::class];

//	protected $favorite_count            = 0;

//	protected $filter_level              = 'low';

//	protected $in_reply_to_screen_name   = '';

//	protected $in_reply_to_status_id_str = '';

//	protected $in_reply_to_user_id_str   = '';

//	protected $is_quote_status           = false;

	protected $lang                      = 'en';

//	protected $place                     = [Place::class];

//	protected $possibly_sensitive        = false;

//	protected $retweet_count             = 0;

//	protected $source                    = '';

	protected $text                      = [TStringNormalize::class];

//	protected $truncated                 = false;

//	protected $user                      = [User::class];

}
