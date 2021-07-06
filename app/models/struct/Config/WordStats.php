<?php


namespace Structure\Config;


use Diskerror\Typed\Scalar\TIntegerUnsigned;
use Diskerror\Typed\TypedClass;

class WordStats extends TypedClass
{
	protected $quantity = [TIntegerUnsigned::class];
	protected $window   = [TIntegerUnsigned::class];
	protected $stop     = [WordList::class];
}
