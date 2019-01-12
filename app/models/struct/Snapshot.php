<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/22/18
 * Time: 4:20 PM
 */

namespace Structure;

use Diskerror\Typed\Date;
use Diskerror\Typed\DateTime;
use Diskerror\Typed\SAString;
use Diskerror\Typed\TypedArray;
use Structure\TagCloud\Word;

class Snapshot extends \Diskerror\Typed\TypedClass
{
	protected $_id      = 0;    //	time()

	protected $created  = [DateTime::class];

	protected $track    = [TypedArray::class, 'string'];

	protected $tagCloud = [TypedArray::class, Word::class];

	protected $summary  = [TypedArray::class, 'string'];
}
