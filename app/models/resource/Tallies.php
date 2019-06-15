<?php

namespace Resource;

use Structure\TallySet;

class Tallies extends MongoCollection
{
	public function __construct()
	{
		$this->_collection = 'tallies';
		$this->_class      = TallySet::class;
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
