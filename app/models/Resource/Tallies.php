<?php

namespace Resource;

use Structure\TallySet;

class Tallies extends MongoCollection
{
	protected $_collection = 'tallies';
	protected $_class      = TallySet::class;

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
