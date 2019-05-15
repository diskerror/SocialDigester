<?php

namespace Structure;

use Diskerror\Typed\ArrayOptions as AO;

class Tallies extends \Diskerror\Typed\TypedClass
{
	protected $created = '__class__Diskerror\Typed\DateTime';

	protected $uniqueHashtags = '__class__Structure\TallyWords()';

	protected $textwords = '__class__Structure\TallyWords()';

	public function __construct($in = null)
	{
		parent::__construct($in);
		$this->setArrayOptions(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::TO_BSON_DATE);
	}
}
