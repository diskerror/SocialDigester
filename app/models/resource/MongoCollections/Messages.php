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
	protected $_indexes        = [
		['key' => ['created' => 1], 'options' => ['expireAfterSeconds' => 36000]],    //	10 hours
	];
}
