<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/22/18
 * Time: 4:20 PM
 */

namespace Structure;

class Snapshot extends \Diskerror\Typed\TypedClass
{
	protected $_map = [
		'_id' => 'id_',            //	from Mongo
	];


	protected $_nullCreatesNullInstance = true;


	protected $id_      = 0;    //	time()

	protected $created  = '__class__Diskerror\Typed\DateTime';

	protected $track    = '__class__Diskerror\Typed\TypedArray(null, "string")';

	protected $tagCloud = '__class__Diskerror\Typed\TypedArray(null, "\Structure\TagCloud\Word")';

	protected $summary  = '__class__Diskerror\Typed\TypedArray(null, "string")';
}
