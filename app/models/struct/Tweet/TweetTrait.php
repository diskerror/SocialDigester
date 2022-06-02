<?php

namespace Structure\Tweet;

use Diskerror\Typed\Scalar\TStringNormalize;

/**
 * Trait TweetTrait
 *
 * @package Structure\Tweet
 *
 * @property Created          $created_at
 * @property                  $contributors
 * @property Entities         $entities
 * @property ExtendedEntities $extended_entities
 * @property int              $favorite_count
 * @property string           $filter_level
 * @property string           $in_reply_to_screen_name
 * @property string           $in_reply_to_status_id_str
 * @property string           $in_reply_to_user_id_str
 * @property bool             $is_quote_status
 * @property string           $lang
 * @property Place            $place
 * @property bool             $possibly_sensitive
 * @property int              $retweet_count
 * @property string           $source
 * @property string           $text
 * @property bool             $truncated
 * @property User             $user
 */
trait TweetTrait
{
	protected $created_at = [Created::class];

//	protected $contributors              = null;

	protected $entities = [Entities::class];

//	protected $extended_entities         = [ExtendedEntities::class];

//	protected $favorite_count            = 0;

//	protected $filter_level              = 'low';

//	protected $in_reply_to_screen_name   = '';

//	protected $in_reply_to_status_id_str = '';

//	protected $in_reply_to_user_id_str   = '';

//	protected $is_quote_status           = false;

	protected $lang = 'en';

//	protected $place                     = [Place::class];

//	protected $possibly_sensitive        = false;

//	protected $retweet_count             = 0;

//	protected $source                    = '';

	protected $text = [TStringNormalize::class];

//	protected $truncated                 = false;

	protected $user = [User::class];

}
