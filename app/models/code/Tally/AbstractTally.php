<?php

namespace Code\Tally;

use InvalidArgumentException;
use Structure\TallyWords;

abstract class AbstractTally
{
	protected static function _normalizeGroupsFromTally(TallyWords $tally, int $quantity, $technique = 'metaphone'): array
	{
		//	Group words by normalized value.
		$normalizedGroups = [];
		foreach ($tally as $k => $v) {
			//	remove trailing digits
			if (0 === preg_match('/^\d+$/', $k)) {
				$k = preg_replace('/\d+$/', '', $k);
			}

			//	So "Schumer" == "Shumer".
			$k = str_ireplace('sch', 'sh', $k);

			//	Plural becomes singular for longer words.
//			if (strlen($s) > 5) {
//				$s = preg_replace('/s+$/i', '', $s);
//			}

			switch ($technique) {
				case 'none':
					$normalized = $k;
					break;

				case 'strtolower':
					$normalized = strtolower($k);
					break;

				case 'metaphone':
					$normalized = metaphone($k);
					break;

				case 'soundex':
					$normalized = soundex($k);
					break;

				case 'stem':
//					$normalized = \Diskerror\stem($s)[0];
//					break;

				default:
					throw new InvalidArgumentException('bad normalize technique');
			}

			$normalizedGroups[$normalized][$k] = $v;
		}

		//	Organize the group's properties.
		foreach ($normalizedGroups as &$group) {
			arsort($group);
			$group['_sum_'] = array_sum($group);
		}

		//	Sort on size, descending.
		uasort($normalizedGroups, 'self::_sortCountSumDesc');

		//	Get the first X number of members.
		$normalizedGroups = array_slice($normalizedGroups, 0, $quantity);

		//	TODO: Do a binary type sort but only on the first $quantity items.

		return $normalizedGroups;
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	private static function _sortCountSumDesc($a, $b)
	{
		if ($a['_sum_'] === $b['_sum_']) {
			return 0;
		}

		return ($a['_sum_'] < $b['_sum_']) ? 1 : -1;
	}
}
