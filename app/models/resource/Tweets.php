<?php

namespace Resource;

use Structure\Tweet;

class Tweets extends MongoCollection
{
	protected $_collectionName = 'tweets';
	protected $_class          = Tweet::class;
	protected $_indexes        = [
		['key' => ['created_at' => 1], 'options' => ['expireAfterSeconds' => 600]],
		['key' => ['entities.hashtags.0.text' => 1]],
		['key' => ['text' => 1]],
	];

}
