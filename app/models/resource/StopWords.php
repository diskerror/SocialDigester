<?php

namespace Resource;

use Ds\Vector;

/**
 * StopWords
 */
class StopWords
{
	/**
	 * @var Vector
	 */
	protected static $_vector;

	/**
	 * @var string[]
	 */
	protected static $_list = [
		'about',
		'all',
		'and',
		'any',
		'are',
		'back',
		'been',
		'being',
		'but',
		'can',
		'could',
		'did',
		'did\'t',
		'even',
		'field',
		'for',
		'from',
		'get',
		'going',
		'had',
		'has',
		'have',
		'her',
		'him',
		'his',
		'how',
		'http',
		'https',
		'into',
		'it\'s',
		'its',
		'just',
		'let',
		'make',
		'man',
		'many',
		'more',
		'most',
		'much',
		'new',
		'not',
		'now',
		'only',
		'other',
		'our',
		'over',
		'really',
		'saw',
		'see',
		'she',
		'since',
		'still',
		'than',
		'that',
		'the',
		'their',
		'them',
		'there',
		'they',
		'this',
		'those',
		'very',
		'via',
		'was',
		'what',
		'when',
		'which',
		'who',
		'why',
		'will',
		'with',
		'would',
		'you',
		'your',
	];

	/**
	 * @param string $word
	 * @return bool
	 */
	public static function contains(string $word): bool
	{
		if (!isset(self::$_vector)) {
			self::$_vector = new Vector(self::$_list);
		}

		return self::$_vector->contains($word);
	}
}
