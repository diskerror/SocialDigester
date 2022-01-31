<?php

namespace Resource\MongoCollections;

use Resource\MongoCollection;
use Structure\Tweet;

class Tweets extends MongoCollection
{
	protected $_collectionName = 'tweets';
	protected $_class          = Tweet::class;
	protected $_indexKeys      = [
		['key' => ['created_at' => 1], 'expireAfterSeconds' => 600],
		['key' => ['entities.hashtags.0.text' => 1]],
	];
}
