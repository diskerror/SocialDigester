<?php

namespace Resource;

use Structure\Tally;

class Tallies extends MongoCollection
{
	protected $_collection = 'tallies';
	protected $_class      = Tally::class;

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
