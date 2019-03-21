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

/**
 * Class Snapshot
 *
 * @package Structure
 *
 * @property $_id
 * @property $created
 * @property $track
 * @property $tagCloud
 * @property $summary
 */
class Snapshot extends TypedClass
{
	protected $_id      = null;

	protected $created  = [DateTime::class];

	protected $track    = [TypedArray::class, 'string'];

	protected $tagCloud = [TypedArray::class, Word::class];

	protected $summary  = [TypedArray::class, 'string'];
}
