<?php

namespace Structure\Config;


use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;

/**
 * Class Mongo
 *
 * @property string $host
 * @property string $database
 *
 */
class Mongo extends TypedClass
{
	protected $host     = [TString::class];
	protected $database = [TString::class];
}
