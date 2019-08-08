<?php

namespace Code\Tally;

use function array_key_exists;
use function array_pop;
use function array_slice;
use function array_sum;
use function arsort;
use function count;
use Ds\Map;
use Ds\PriorityQueue;
use InvalidArgumentException;
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
		foreach ($tally as $realName => $v) {
			$normalizedGroups[self::_normalizeText($realName, $technique)][$realName] = $v;
		}

		//	Get the sum of the counts of real names.
		foreach ($normalizedGroups as &$group) {
			$group['_sum_'] = array_sum($group);
		}

		//	Sort on size, descending.
		$groupsPQ = new PriorityQueue();
		$groupsPQ->allocate($quantity);        //	always allocates to the next power of 2

		foreach ($normalizedGroups as $normGroup) {
			$groupsPQ->push($normGroup, $normGroup['_sum_']);
		}

		return array_slice($groupsPQ->toArray(), 0, $quantity);	//	Now return the exact number.

//		$groups = [];
//		$i      = 0;
//		foreach ($normalizedGroups as $name => $normGroup) {
//			++$i;
//			$groups[$name] = $normGroup;
//			if ($i === $quantity) {
//				break;
//			}
//		}
//
//		uasort($groups, 'self::_sortCountSumDesc');
//
//		if ($i < $quantity || count($normalizedGroups) === $quantity) {
//			return $groups;
//		}
//
//		$normalizedGroups = array_slice($normalizedGroups, $quantity);
//
//		$last = $quantity - 1;
//		foreach ($normalizedGroups as $name => $normGroup) {
//			if ($normGroup['_sum_'] < end($groups)['_sum_']) {
//				continue;
//			}
//			$groups[$name] = $normGroup;
//			uasort($groups, 'self::_sortCountSumDesc');
//			array_pop($groups);
//		}
//
//		return $groups;
	}

	private static function _sortCountSumDesc($a, $b)
	{
		if ($a['_sum_'] === $b['_sum_']) {
			return 0;
		}

		return ($a['_sum_'] < $b['_sum_']) ? 1 : -1;
	}
}
