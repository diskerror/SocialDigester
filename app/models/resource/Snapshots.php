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
	protected $_collection = 'snapshots';
	protected $_class      = Snapshot::class;

	public function doIndex(int $expire=0)
	{
		$this->getClient()->insertOne([]);
		$this->getClient()->dropIndexes();
		$this->getClient()->createIndex(
			['created' => 1]
		);
	}
}
