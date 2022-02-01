<?php

namespace Structure;

/**
 *
 */
class SearchTerms
{
	/**
	 * @var string[]
	 */
	protected static $_terms = [
		'atf',
		'attorney',
		'cia',
		'congress',
		'constitution',
		'constitutionparty',
		'court',
		'democracy',
		'democrat',
		'democratic',
		'democraticparty',
		'democratparty',
		'diplomacy',
		'diplomatic',
		'doj',
		'fbi',
		'gop',
		'government',
		'green',
		'greenparty',
		'ice',
		'justice',
		'libertarian',
		'libertarianparty',
		'potus',
		'presidency',
		'president',
		'representative',
		'republic',
		'republican',
		'republicanparty',
		'scotus',
		'senate',
		'senator',
		'socialdemocrat',
		'socialdemocraticparty',
		'supreme',
		'supremecourt',
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
