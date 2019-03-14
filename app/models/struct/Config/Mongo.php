<?php

namespace Structure\Config;

use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;

/**
 * Class Mongo
 *
 * @package Structure\Config
 *
 * @property $host
 * @property $database
 * @property $collections
 */
class Mongo extends TypedClass
{
    protected $host        = 'mongodb://localhost:27017';
    protected $database    = 'digester';
    protected $collections = [TypedArray::class, MongoCollection::class];
}
