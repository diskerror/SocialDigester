<?php

namespace Twitter\Tweet;

class User extends \Diskerror\Typed\TypedClass
{
	protected $id_str = '';
	protected $name  = '';
	protected $screen_name  = '';
	protected $location  = '';
	protected $url  = '';
	protected $description  = '';
	protected $protected = false;
	protected $verified = false;
	protected $followers_count = 0;
	protected $friends_count = 0;
	protected $listed_count = 0;
	protected $favourites_count = 0;
	protected $statuses_count = 0;
	protected $created_at = '__class__Diskerror\Utilities\DateTime';
	protected $time_zone = '';
	protected $geo_enabled = false;
	protected $lang = 'en';
	protected $contributors_enabled = false;
	protected $is_translator = false;

	protected function _set_description($v)
	{
		$this->description = preg_replace( '/\s+/', ' ', \Normalizer::normalize((string) $v, \Normalizer::FORM_D) );
	}

}
