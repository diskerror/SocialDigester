<?php

namespace Logic;

/**
 *
 */
class SearchTerms
{
	/**
	 * @var string[]
	 */
	protected static $_terms = [
		'baltic',
		'biden',
		'congress',
		'constitution',
		'court',
		'democracy',
		'democratic',
		'diplomacy',
		'diplomatic',
		'eu',
		'europe',
		'european',
		'europeanunion',
		'government',
		'justice',
		'libertarian',
		'nato',
		'potus',
		'presidency',
		'president',
		'putin',
		'representative',
		'republic',
		'russia',
		'russian',
		'scotus',
		'senate',
		'senator',
		'supreme',
		'supremecourt',
		'ukraine',
		'ukrainian',
		'warsawpact',
		'zelensky',
	];

	/**
	 * @return string[]
	 */
	public static function get()
	{
		return self::$_terms;
	}

	/**
	 * @param $delim
	 * @return string
	 */
	public static function implode($delim = ',')
	{
		return implode($delim, self::$_terms);
	}
}
