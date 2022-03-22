<?php

namespace Structure;

use Diskerror\TypedBSON\DateTime;
use Diskerror\TypedBSON\TypedArray;
use Diskerror\TypedBSON\TypedClass;
use Structure\TagCloud\Word;

/**
 * Class Snapshot
 *
 * @package Structure
 *
 * @property $_id
 * @property DateTime $created
 * @property array $track
 * @property array $tagCloud
 * @property array $summary
 */
class Snapshot extends TypedClass
{
	protected $_id;
	protected $created  = [DateTime::class];
	protected $track    = [TypedArray::class, 'string'];
	protected $tagCloud = [TypedArray::class, Word::class];
	protected $summary  = [TypedArray::class, 'string'];
}
