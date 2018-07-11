<?php

namespace Tweet;

use Diskerror\Typed\ArrayOptions as AO;

class Tweet extends TweetBase
{
	protected $_arrayOptions    = new AO(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::KEEP_JSON_EXPR);

	protected $retweeted_status = '__class__Tweet\TweetBase';
}
