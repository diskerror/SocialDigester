<?php

namespace Resource;

use Structure\Tally;

class Tallies extends MongoCollection
{
	protected $_collectionName = 'tallies';
	protected $_class          = Tally::class;

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
