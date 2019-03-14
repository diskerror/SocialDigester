<?php

namespace Structure\Config;

use Diskerror\Typed\TypedArray;

class MongoCollection extends TypedArray
{
    protected $_type = MongoIndexDef::class;
}
