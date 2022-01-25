<?php

namespace Logic\Cloud;

use Diskerror\Typed\TypedArray;
use Ds\Set;
use Logic\AbstractTally;
use MongoDB\BSON\UTCDateTime;
use Resource\Tallies;
use Structure\Config;
use Structure\TagCloud\Word;
use Structure\TallyWords;

abstract class AbstractCloud extends AbstractTally
{
	/**
	 * Format data with TagCloud object.
	 * Words are normalized and grouped under the same tag.
	 *
	 * @param TallyWords $tally
	 * @param int $window
	 * @param int $quantity
	 * @param string $technique
	 *
	 * @return TypedArray
	 */
	protected static function _buildTagCloud(TallyWords $tally, int $window, int $quantity, $technique = 'metaphone'): TypedArray
	{
		$tally->scaleTally($window / 60.0); // changes value to count per minute

		$normalizedGroups = self::_normalizeGroupsFromTally($tally, $quantity, $technique);

		//	Sort on key.
		ksort($normalizedGroups, SORT_NATURAL | SORT_FLAG_CASE);

		$cloudWords = new TypedArray(Word::class);
		foreach ($normalizedGroups as &$group) {
			$totalTally = $group['_sum_'];
			unset($group['_sum_']);
			$groupKeys     = array_keys($group);
			$htmlTitle     = '';
			$twitterLookup = new Set();

			foreach ($group as $thisName => $thisTally) {
				$twitterLookup->add(strtolower($thisName));

				if (count($group) > 1) {
					$htmlTitle .= '<br>' . $thisName . ': ' . (string) $thisTally;
				}
			}

			$cloudWords[] = [
				'text'   => $groupKeys[0],
				'weight' => (int) ((log($totalTally * 5) * 40) + $totalTally * 5),   //  A combination of log and linear.
				'link'   => 'javascript:ToTwitter(["' . implode('","', $twitterLookup->toArray()) . '"])',
				'html'   => [
					'title' => $totalTally . $htmlTitle,
					// 	'url' => 'https://twitter.com/search?f=tweets&vertical=news&q=%23' . implode('%20OR%20%23', $twitterLookup->toArray())
				],
			];
		}

		return $cloudWords;
	}

}
