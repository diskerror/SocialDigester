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
		$this->_class      = '\Structure\Tweet';
	}

	public function doIndex(int $expire=0)
	{
		$this->getClient()->insertOne([]);
		$this->getClient()->dropIndexes();
		$this->getClient()->createIndex(
			['created_at' => 1, 'entities.hashtags.0.text' => 1],
			['expireAfterSeconds' => $expire]
		);

		$this->getClient()->createIndex(
			['created_at' => 1, 'text' => 1],
			['expireAfterSeconds' => $expire]
		);
	}
}
