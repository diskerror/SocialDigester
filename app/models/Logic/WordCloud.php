<?php

namespace Logic;

use Diskerror\Typed\TypedArray;
use Ds\Set;
use Structure\TagCloud\Word;

final class WordCloud
{
	private function __construct() { }

	/**
	 * Format data with TagCloud object.
	 * Words are normalized and grouped under the same tag.
	 *
	 * @param array $normalizedGroups
	 *
	 * @return TypedArray
	 */
	static function build(array $normalizedGroups): TypedArray
	{
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
					$htmlTitle .= '<br>' . $thisName . ': ' . $thisTally;
				}
			}

			$cloudWords[] = [
				'text'   => $groupKeys[0],
				'weight' => ((log($totalTally * 5) * 40) + $totalTally * 5),   //  A combination of log and linear.
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
