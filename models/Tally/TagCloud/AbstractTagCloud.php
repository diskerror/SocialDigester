<?php

namespace Tally\TagCloud;

use Ds\Set;
use Diskerror\Typed\TypedArray;

class AbstractTagCloud extends \Tally\AbstractTally
{
	/**
	 * Format data with TagCloud object.
	 * Words are normalized and grouped under the same tag.
	 *
	 * @param Phalcon\Config $config
	 * @return array
	 */
	protected function _buildTagCloud($config)
	{
		//	Group words by normalized value.
		$normalizedGroup = [];
		foreach ( $this->_tally as $k=>$v ) {
			$normalizedGroup[self::_normalizeText($k)][$k] = $v;
		}

		//	Sort on size, decending.
		uasort($normalizedGroup, 'self::_sortCountSumDesc');

		//	Get the first X number of members.
		$normalizedGroup = array_slice($normalizedGroup, 0, $config->count);

		//	Sort on key.
		ksort($normalizedGroup, SORT_NATURAL | SORT_FLAG_CASE);

		$cloudWords = new TypedArray(null, 'TagCloud\Word');
		foreach ( $normalizedGroup as $group ) {
			$largestTally = 0;
			$totalTally = 0;
			$htmlTitle = '';
			$twitterLookup = new Set();

			//	find the most used capitalization/spelling style in the word group
			foreach ( $group as $origName => $thisTally ) {
				$totalTally += $thisTally;
				$twitterLookup->add(strtolower($origName));

				if ( $thisTally > $largestTally ) {
					$popularName = $origName;
					$largestTally = $thisTally;
				}

				if ( count($group) > 1 ) {
					$htmlTitle .= '<br>' . $origName . ': ' . (string)$thisTally;
				}
			}

			$cloudWords[] = [
				'text' => $popularName,
				'weight' => (int) (log($totalTally)*50)+$totalTally,   //  A combination of log and linear.
				'link' => 'javascript:ToTwitter(["' . implode('","', $twitterLookup->toArray()) . '"])',
				'html' => [
					'title' => $totalTally . $htmlTitle,
// 					'url' => 'https://twitter.com/search?f=tweets&vertical=news&q=%23' . implode('%7c%23', $twitterLookup->toArray())
				]
			];
		}

		return $cloudWords->getSpecialObj(['dateToBsonDate'=>false]);
	}

	private static function _normalizeText($s)
	{
		//	So "Schumer" == "Shumer".
		$s = preg_replace('/sch/i', 'sh', $s);

		//	Plural becomes singular for longer words.
		if ( strlen($s) > 5) {
			$s = preg_replace('/s+$/i', '', $s);
		}

// 		return strtolower($s);
		return metaphone($s);
// 		return soundex($s);
	}

	private static function _sortCountSumDesc($a, $b)
	{
		$aSum = array_sum($a);
		$bSum = array_sum($b);

		if ($aSum === $bSum) {
			return 0;
		}

		return ($aSum > $bSum) ? -1 : 1;
	}

}
