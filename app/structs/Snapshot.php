<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/22/18
 * Time: 4:20 PM
 */

class Snapshot extends \Diskerror\Typed\TypedClass
{
	protected $_map = [
		'_id' => 'id_',			//	from Mongo
	];

	protected $id_      = 0;	//	time()

	protected $created  = '__class__Diskerror\Utilities\DateTime';

	protected $track    = '__class__Diskerror\Typed\TypedArray(null, "string")';

	protected $tagCloud = '__class__Diskerror\Typed\TypedArray(null, "TagCloud\Word")';

	protected $summary  = '__class__Diskerror\Typed\TypedArray(null, "string")';
}
