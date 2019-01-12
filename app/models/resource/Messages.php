<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 7/17/18
 * Time: 11:45 AM
 */

namespace Resource;

class Messages extends MongoCollection
{
	public function __construct()
	{
		$this->_collection = 'messages';
		$this->_class      = '';
	}

	public function doIndex(int $expire=0)
	{
		$this->getClient()->insertOne([]);
		$this->getClient()->dropIndexes();
		$this->getClient()->createIndex(
			['created' => 1],
			['expireAfterSeconds' => $expire]
		);
	}
}
