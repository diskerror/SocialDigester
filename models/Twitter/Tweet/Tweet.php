<?php

namespace Twitter\Tweet;

class Tweet extends \Diskerror\Typed\TypedClass
{
	protected $id_ = 0;
	protected $created_at = '__class__Diskerror\Utilities\DateTime';
	protected $text = '';
	protected $source = '';
	protected $truncated = false;
	protected $in_reply_to_status_id_str = '';
	protected $in_reply_to_user_id_str = '';
	protected $in_reply_to_screen_name = '';
	protected $user = '__class__Twitter\Tweet\User';
	protected $place = '__class__Twitter\Tweet\Place';
	protected $contributors = null;
// 	protected $retweeted_status = '__class__Twitter\Tweet\Tweet';
	protected $is_quote_status = false;
	protected $retweet_count = 0;
	protected $favorite_count = 0;
	protected $entities = '__class__Twitter\Tweet\Entities';
	protected $possibly_sensitive = false;
	protected $filter_level = 'low';
	protected $lang = 'en';

	//	Additional fields for start of analysis.
	protected $hashtags = '__class__Diskerror\Typed\TypedArray(null, "string")';
	protected $words = '__class__Diskerror\Typed\TypedArray(null, "string")';
	protected $pairs = '__class__Diskerror\Typed\TypedArray(null, "string")';

	protected $_map = [
		'id' => 'id_'
	];

	protected function _set_text($v)
	{
		$this->text = preg_replace( '/\s+/', ' ', \Normalizer::normalize((string) $v, \Normalizer::FORM_D) );
	}

}
