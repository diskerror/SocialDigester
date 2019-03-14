<?php

namespace Structure\Tweet;

use Diskerror\Typed\DateTime;
use Structure\NormalizeString;
use Structure\Tweet\Entities\Entities;

trait TweetTrait
{
	protected $created_at                = [DateTime::class];

	protected $contributors              = null;

	protected $entities                  = [Entities::class];

//	protected $extended_entities         = [ExtendedEntities::class];

	protected $favorite_count            = 0;

	protected $filter_level              = 'low';

	protected $in_reply_to_screen_name   = '';

	protected $in_reply_to_status_id_str = '';

	protected $in_reply_to_user_id_str   = '';

	protected $is_quote_status           = false;

	protected $lang                      = 'en';

	protected $place                     = [Place::class];

	protected $possibly_sensitive        = false;

	protected $retweet_count             = 0;

	protected $source                    = '';

	protected $text                      = [NormalizeString::class];

	protected $truncated                 = false;

	protected $user                      = [User::class];

}
