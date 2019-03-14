<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/22/18
 * Time: 4:20 PM
 */

namespace Structure;

use Diskerror\Typed\DateTime;
use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;
use Structure\TagCloud\Word;

class Snapshot extends TypedClass
{
	protected $_id      = 0;    //	time()

	protected $created  = [DateTime::class];

	protected $track    = [TypedArray::class, 'string'];

	protected $tagCloud = [TypedArray::class, Word::class];

	protected $summary  = [TypedArray::class, 'string'];
}
