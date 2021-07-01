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
	protected $_collectionName = 'messages';
	protected $_class          = '';

	public function doIndex(int $expire = 0)
	{
		$this->_collection->insertOne([]);
		$this->_collection->dropIndexes();
		$this->_collection->createIndex(
			['created' => 1],
			['expireAfterSeconds' => $expire]
		);
	}
}
