<?php

namespace Structure\Config;


use Diskerror\Typed\TypedClass;

/**
 * Class Mongo
 *
 * @property string $host
 * @property string $database
 *
 */
class MongoDB extends TypedClass
{
	protected $host     = 'mongodb://localhost:27017';
	protected $database = 'digester';
}
