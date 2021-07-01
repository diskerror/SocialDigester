<?php

namespace Resource;

use Structure\Tweet;

class Tweets extends MongoCollection
{
	protected $_collectionName = 'tweets';
	protected $_class          = Tweet::class;

	public function doIndex(int $expire=0)
	{
		$this->_collection->insertOne([]);
		$this->_collection->dropIndexes();
		$this->_collection->createIndex(
			['created_at' => 1],
			['expireAfterSeconds' => $expire]
		);

		$this->_collection->createIndex(
			['entities.hashtags.0.text' => 1]
		);

		$this->_collection->createIndex(
			['text' => 1]
		);
	}
}
