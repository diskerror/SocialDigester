<?php

namespace Structure;

use Diskerror\Typed\ArrayOptions as AO;

/**
 * Class Tallies
 *
 * @package Structure
 *
 * @property Diskerror\Typed\DateTime $created
 * @property TallyWords               $uniqueHashtags
 * @property TallyWords               $allHashtags
 * @property TallyWords               $textWords
 * @property TallyWords               $userMentions
 */
class Tallies extends \Diskerror\Typed\TypedClass
{
	protected $created = '__class__Diskerror\Typed\DateTime';

	protected $uniqueHashtags = '__class__Structure\TallyWords()';

	protected $allHashtags = '__class__Structure\TallyWords()';

	protected $textWords = '__class__Structure\TallyWords()';

	protected $userMentions = '__class__Structure\TallyWords()';

	public function __construct($in = null)
	{
		parent::__construct($in);
		$this->setArrayOptions(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::TO_BSON_DATE);
	}
}
