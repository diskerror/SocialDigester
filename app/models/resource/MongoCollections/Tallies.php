<?php

namespace Resource\MongoCollections;

use Resource\MongoCollection;
use Structure\Tally;

class Tallies extends MongoCollection
{
	protected $_collectionName = 'tallies';
	protected $_class          = Tally::class;
	protected $_indexKeys      = [
		['key' => ['created' => 1], 'expireAfterSeconds' => 1800],
	];
}
