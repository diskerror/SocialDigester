<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/28/18
 * Time: 12:49 PM
 */

namespace Resource;

use Structure\Snapshot;

class Snapshots extends MongoCollection
{
	public function __construct()
	{
		$this->_collection = 'snapshots';
		$this->_class      = Snapshot::class;
	}

	public function doIndex(int $expire=0)
	{
		$this->getClient()->insertOne([]);
		$this->getClient()->dropIndexes();
		$this->getClient()->createIndex(
			['created' => 1]
		);
	}
}
