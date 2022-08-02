<?php
/** @noinspection ALL */

namespace Logic;

use InvalidArgumentException;
use Structure\TallyWords;
use function Diskerror\stem;

class TextGroup
{
	private function __construct() { }

	public static function normalize(TallyWords $tally, $technique = 'metaphone'): array
	{
		//	Group words by normalized value.
		$normalizedGroups = [];
		foreach ($tally as $k => $v) {
			$normalizedGroups[static::_normalizeString($k, $technique)][$k] = $v;
		}

		//	Organize the group's properties.
		foreach ($normalizedGroups as &$group) {
			arsort($group);
			$group['_sum_'] = array_sum($group);
		}

		//	Sort on size, descending.
		uasort($normalizedGroups, 'static::_sortCountSumDesc');

		return $normalizedGroups;
	}

	protected static function _normalizeString($s, $technique): string
	{
		//	remove trailing digits
		if (0 === preg_match('/^\d+$/', $s)) {
			$s = preg_replace('/\d+$/', '', $s);
		}

		//	So "Schumer" == "Shumer".
		$s = preg_replace('/sch/i', 'sh', $s);

		//	Plural becomes singular for longer words.
//		if (strlen($s) > 5) {
//			$s = preg_replace('/s+$/i', '', $s);
//		}

		//	could use "match" in PHP8
		switch ($technique) {
			case 'none':
			case '':
				return $s;

			case 'strtolower':
				return strtolower($s);

			case 'metaphone':
				return strlen($s) > 3 ? metaphone($s) : strtolower($s);

			case 'soundex':
				return strlen($s) > 3 ? soundex($s) : strtolower($s);

			case 'stem':
				return stem($s)[0];

			default:
				throw new InvalidArgumentException('bad normalize technique');
		}
	}

	private static function _sortCountSumDesc($a, $b): int
	{
		if ($a['_sum_'] === $b['_sum_']) {
			return 0;
		}

		return ($a['_sum_'] < $b['_sum_']) ? 1 : -1;
	}
}
