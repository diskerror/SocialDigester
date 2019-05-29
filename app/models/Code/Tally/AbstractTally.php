<?php

namespace Code\Tally;

use function array_sum;
use InvalidArgumentException;
use function preg_match;
use Structure\TallyWords;

abstract class AbstractTally
{
	protected static function _normalizeText($s, $technique = 'metaphone')
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

		switch ($technique) {
			case 'none':
				return $s;

			case 'strtolower':
				return strtolower($s);

			case 'metaphone':
				return metaphone($s);

			case 'soundex':
				return soundex($s);

			case 'stem':
				return \Diskerror\stem($s)[0];

			default:
				throw new InvalidArgumentException('bad normalize technique');
		}
	}

	protected static function _normalizeGroupsFromTally(TallyWords $tally, int $quantity, $technique = 'metaphone'): array
	{
		//	Group words by normalized value.
		$normalizedGroups = [];
		foreach ($tally as $k => $v) {
			$normalizedGroups[self::_normalizeText($k, $technique)][$k] = $v;
		}

		//	Organize the group's properties.
		foreach ($normalizedGroups as &$group) {
			arsort($group);
			$group['_sum_'] = array_sum($group);
		}

		//	Sort on size, decending.
		uasort($normalizedGroups, 'self::_sortCountSumDesc');

		//	Get the first X number of members.
		$normalizedGroups = array_slice($normalizedGroups, 0, $quantity);

		return $normalizedGroups;
	}

	private static function _sortCountSumDesc($a, $b)
	{
		if ($a['_sum_'] === $b['_sum_']) {
			return 0;
		}

		return ($a['_sum_'] < $b['_sum_']) ? 1 : -1;
	}
}
