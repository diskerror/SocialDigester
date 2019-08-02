<?php

namespace Resource;

use Structure\Tweet;

class Tweets extends MongoCollection
{
	protected $_collection = 'tweets';
	protected $_class      = Tweet::class;

	public function doIndex(int $expire=0)
	{
		$this->getClient()->insertOne([]);
		$this->getClient()->dropIndexes();
		$this->getClient()->createIndex(
			['created_at' => 1],
			['expireAfterSeconds' => $expire]
		);

		$this->getClient()->createIndex(
			['entities.hashtags.0.text' => 1]
		);

		$this->getClient()->createIndex(
			['text' => 1]
		);
	}
}
