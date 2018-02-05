<?php

namespace Tally\TagCloud;

use Ds\Set;
use Diskerror\Typed\TypedArray;

class AbstractTagCloud extends \Tally\AbstractTally
{
	private static function _sortCountSumDesc($a, $b)
	{
		if ($a['_sum_'] === $b['_sum_']) {
			return 0;
		}

		return ($a['_sum_'] > $b['_sum_']) ? -1 : 1;
	}

	/**
	 * Format data with TagCloud object.
	 * Words are normalized and grouped under the same tag.
	 *
	 * @param Phalcon\Config $config
	 *
	 * @return array
	 */
	protected function _buildTagCloud($config)
	{
		//	Group words by normalized value.
		$normalizedGroup = [];
		foreach ($this->_getTally() as $k => $v) {
			$normalizedGroup[self::_normalizeText($k)][$k] = $v;
		}

		//	Organize the group's properties.
		foreach ($normalizedGroup as &$group) {
			arsort($group);
			$group['_sum_'] = array_sum($group);
		}

		//	Sort on size, decending.
		uasort($normalizedGroup, 'self::_sortCountSumDesc');

		//	Get the first X number of members.
		$normalizedGroup = array_slice($normalizedGroup, 0, $config->count);

		//	Sort on key.
		ksort($normalizedGroup, SORT_NATURAL | SORT_FLAG_CASE);

		$cloudWords = new TypedArray(null, 'TagCloud\Word');
		foreach ($normalizedGroup as &$group) {
			$totalTally = $group['_sum_'];
			unset($group['_sum_']);
			$groupKeys = array_keys($group);
			$htmlTitle = '';
			$twitterLookup = new Set();

			foreach ($group as $thisName => $thisTally) {
				$twitterLookup->add(strtolower($thisName));

				if (count($group) > 1) {
					$htmlTitle .= '<br>' . $thisName . ': ' . (string)$thisTally;
				}
			}

			$cloudWords[] = [
				'text'   => $groupKeys[0],
				'weight' => (int)(log($totalTally) * 50) + $totalTally,   //  A combination of log and linear.
				'link'   => 'javascript:ToTwitter(["' . implode('","', $twitterLookup->toArray()) . '"])',
				'html'   => [
					'title' => $totalTally . $htmlTitle,
					// 	'url' => 'https://twitter.com/search?f=tweets&vertical=news&q=%23' . implode('%7c%23', $twitterLookup->toArray())
				],
			];
		}

		return $cloudWords->getSpecialObj(['dateToBsonDate' => false]);
	}

	private static function _normalizeText($s)
	{
		//	So "Schumer" == "Shumer".
		$s = preg_replace('/sch/i', 'sh', $s);

		//	Plural becomes singular for longer words.
		if (strlen($s) > 5) {
			$s = preg_replace('/s+$/i', '', $s);
		}

// 		return strtolower($s);
		return metaphone($s);
// 		return soundex($s);
	}

}
