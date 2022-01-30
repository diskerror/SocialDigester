<?php

namespace Resource\MongoCollections;

use Resource\MongoCollection;
use Structure\Tally;

class Tallies extends MongoCollection
{
	protected $_collectionName = 'tallies';
	protected $_class          = Tally::class;
	protected $_indexes        = [
		['key' => ['created' => 1], 'options' => ['expireAfterSeconds' => 600]],
	];

}
