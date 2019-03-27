<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 2019-01-31
 * Time: 12:35
 */

namespace Structure\Config;


use Diskerror\Typed\SAString;
use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;

class WordStats extends TypedClass
{
	protected $quantity  = 100;	//	return the top X items
	protected $window    = 300;	//	summarize the last X seconds
	protected $stopWords = [TypedArray::class, SAString::class];
}
