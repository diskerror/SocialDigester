<?php

namespace Structure;

class Snapshot extends \Diskerror\Typed\TypedClass
{
	protected $_nullCreatesNullInstance = true;


	protected $_id      = 0;    //	time()

	protected $created  = ['Diskerror\Typed\DateTime'];

	protected $track    = ['Diskerror\Typed\TypedArray', 'string'];

	protected $tagCloud = 'Diskerror\Typed\TypedArray(null, "\Structure\TagCloud\Word")';

	protected $summary  = 'Diskerror\Typed\TypedArray(null, "string")';
}
