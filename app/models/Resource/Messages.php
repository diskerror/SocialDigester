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
	protected $_collection = 'messages';
	protected $_class      = '';

	public function doIndex(int $expire = 0)
	{
		$this->getClient()->insertOne([]);
		$this->getClient()->dropIndexes();
		$this->getClient()->createIndex(
			['created' => 1],
			['expireAfterSeconds' => $expire]
		);
	}
}
