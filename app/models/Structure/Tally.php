<?php

namespace Structure;

use Diskerror\TypedBSON\DateTime;
use Diskerror\TypedBSON\TypedClass;

/**
 * Class Tallies
 *
 * @package Structure
 *
 * @property DateTime   $created
 * @property TallyWords $uniqueHashtags
 * @property TallyWords $allHashtags
 * @property TallyWords $textWords
 * @property TallyWords $userMentions
 */
class Tally extends TypedClass
{
	protected $created = [DateTime::class];

	protected $uniqueHashtags = [TallyWords::class];

	protected $allHashtags = [TallyWords::class];

	protected $textWords = [TallyWords::class];

	protected $userMentions = [TallyWords::class];
}
