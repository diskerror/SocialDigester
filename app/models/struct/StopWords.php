<?php

namespace Structure;

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
		'after',
		'all',
		'and',
		'any',
		'are',
		'back',
		'been',
		'before',
		'being',
		'but',
		'can',
		'could',
		'did',
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
	 * @param $name
	 * @param $arguments
	 * @return false|mixed
	 */
	public static function __callStatic($name, $arguments = [])
	{
		if (!isset(self::$_vector)) {
			self::$_vector = new Vector(self::$_list);
		}

		return call_user_func_array([self::$_vector, $name], $arguments);
	}
}
