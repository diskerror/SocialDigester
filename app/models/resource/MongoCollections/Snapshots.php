<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/28/18
 * Time: 12:49 PM
 */

namespace Resource\MongoCollections;

use Resource\MongoCollection;
use Structure\Snapshot;

class Snapshots extends MongoCollection
{
	protected $_collectionName = 'snapshots';
	protected $_class          = Snapshot::class;
	protected $_indexes        = [
		['key' => ['created' => 1]],
	];

}
