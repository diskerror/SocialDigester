<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 7/17/18
 * Time: 11:45 AM
 */

namespace Resource\MongoCollections;

use Resource\MongoCollection;

class Messages extends MongoCollection
{
	protected $_collectionName = 'messages';
	protected $_class          = '';
	protected $_indexKeys      = [
		['key' => ['created' => 1], 'expireAfterSeconds' => 3600 * 24],
	];
}
