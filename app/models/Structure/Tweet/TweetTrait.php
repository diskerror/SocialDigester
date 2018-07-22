<?php

namespace Structure\Tweet;

trait TweetTrait
{
	protected $created_at                = '__class__Diskerror\Typed\DateTime';

	protected $contributors              = null;

	protected $entities                  = '__class__Structure\Tweet\Entities\Entities';

//	protected $extended_entities         = '__class__Structure\Tweet\ExtendedEntities';

	protected $favorite_count            = 0;

	protected $filter_level              = 'low';

	protected $in_reply_to_screen_name   = '';

	protected $in_reply_to_status_id_str = '';

	protected $in_reply_to_user_id_str   = '';

	protected $is_quote_status           = false;

	protected $lang                      = 'en';

	protected $place                     = '__class__Structure\Tweet\Place';

	protected $possibly_sensitive        = false;

	protected $retweet_count             = 0;

	protected $source                    = '';

	protected $text                      = '';

	protected $truncated                 = false;

	protected $user                      = '__class__Structure\Tweet\User';


	protected function _set_text($v)
	{
		$this->text = preg_replace('/\s+/', ' ', \Normalizer::normalize((string)$v, \Normalizer::FORM_D));
	}

}
