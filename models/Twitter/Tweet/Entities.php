<?php

namespace Twitter\Tweet;

class Entities extends \Diskerror\Typed\TypedClass
{
	protected $hashtags = '__class__Diskerror\Typed\TypedArray(null, "Twitter\Tweet\Hashtags")';
	protected $urls  = '__class__Diskerror\Typed\TypedArray(null, "Twitter\Tweet\Urls")';
	protected $user_mentions  = '__class__Diskerror\Typed\TypedArray(null, "Twitter\Tweet\UserMentions")';
// 	protected $symbols  = '';
}
