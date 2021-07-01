<?php


namespace Structure\Config;


use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;

class TwitterAuth extends TypedClass
{
	protected $consumer_key       = [TString::class];
	protected $consumer_secret    = [TString::class];
	protected $oauth_token        = [TString::class];
	protected $oauth_token_secret = [TString::class];
}
