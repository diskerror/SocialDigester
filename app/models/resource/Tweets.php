<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/28/18
 * Time: 12:49 PM
 */

namespace Resource;

class Tweets extends MongoCollection
{
	public function __construct()
	{
		$this->_collection = 'tweets';
	}

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
