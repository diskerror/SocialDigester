<?php

namespace Tweet;

class TweetBase extends \Diskerror\Typed\TypedClass
{
	protected $_map = [
		'id'  => 'id_',    //	from Twitter
		'_id' => 'id_',    //	from Mongo
	];

	protected $_nullCreatesNullInstance = true;

	//	Only this top level "id" is used for the MongoDb "_id" auto index.
	//	The "getSpecialArr" method changes "id_" to "_id".
	protected $id_ = 0;
	protected $created_at = '__class__Diskerror\Utilities\DateTime';
	protected $contributors = null;
	protected $entities = '__class__Tweet\Entities\Entities';
// 	protected $extended_entities = '__class__Tweet\ExtendedEntities';
	protected $favorite_count = 0;
	protected $filter_level = 'low';
	protected $in_reply_to_screen_name = '';
	protected $in_reply_to_status_id_str = '';
	protected $in_reply_to_user_id_str = '';
	protected $is_quote_status = false;
	protected $lang = 'en';
	protected $place = '__class__Tweet\Place';
	protected $possibly_sensitive = false;
	protected $retweet_count = 0;
	protected $source = '';
	protected $text = '';
	protected $truncated = false;
	protected $user = '__class__Tweet\User';


	protected function _set_text($v)
	{
		$this->text = preg_replace('/\s+/', ' ', \Normalizer::normalize((string)$v, \Normalizer::FORM_D));
	}

}
