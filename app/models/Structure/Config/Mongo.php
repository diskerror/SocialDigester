<?php

namespace Structure\Config;


use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;

/**
 * Class Mongo
 *
 * @package Structure\Config
 *
 * @param $host
 * @param $database
 */
class Mongo extends TypedClass
{
	protected $host     = [TString::class];
	protected $database = [TString::class];
}
