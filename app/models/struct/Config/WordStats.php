<?php


namespace Structure\Config;


use Diskerror\Typed\Scalar\TIntegerUnsigned;
use Diskerror\Typed\TypedClass;

/**
 * Class WordStats
 *
 * @property int      $quantity
 * @property int      $window
 * @property WordList $stop
 *
 * @package Structure\Config
 */
class WordStats extends TypedClass
{
	protected $quantity = [TIntegerUnsigned::class];
	protected $window   = [TIntegerUnsigned::class];
	protected $stop     = [WordList::class];
}
