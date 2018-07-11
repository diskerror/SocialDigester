<?php

namespace Tweet;

use Diskerror\Typed\ArrayOptions as AO;

class Tweet extends TweetBase
{
	public function __construct($in=null)
	{
		parent::__construct($in);
		$this->setArrayOptions(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::KEEP_JSON_EXPR);
	}

	protected $retweeted_status = '__class__Tweet\TweetBase';
}
