<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/26/18
 * Time: 1:00 PM
 */

namespace Code\Tally;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;
use Phalcon\Config;
use Ds\Set;
use Diskerror\Typed\TypedArray;
use Code\TallyWords;
use Resource\Tweets;
use Structure\TagCloud\Word;

final class TagCloud extends AbstractTally
{
	private function __construct() { }

	/**
	 * Return count of each current hashtag.
	 *
	 * @param Config $config
	 *
	 * @return TypedArray
	 */
	public static function getHashtags(Config $config) : TypedArray
	{
		$tweets = (new \Resource\Tweets())->find([
			'created_at'               => ['$gt' => new \MongoDB\BSON\UTCDateTime(strtotime($config->window . ' seconds ago') * 1000)],
			'entities.hashtags.0.text' => ['$gt' => ''],
		]);

		$uniqeWords = new Set();
		$tally      = new TallyWords();
		foreach ($tweets as $tweet) {
			if (preg_match('/(^039|^rt$)/i', $tweet->text)) {
				continue;
			}

			//	Make sure we have only one of a hashtag per tweet.
			$uniqeWords->clear();
			foreach ($tweet->entities->hashtags as $hashtag) {
				$uniqeWords->add($hashtag->text);
			}

			foreach ($uniqeWords as $uniqeWord) {
				$tally->doTally($uniqeWord);
			}
		}

		return self::_buildTagCloud($tally, $config);
	}

	/**
	 * Format data with TagCloud object.
	 * Words are normalized and grouped under the same tag.
	 *
	 * @param TallyWords $tally
	 * @param Config     $config
	 *
	 * @return TypedArray
	 */
	private static function _buildTagCloud(TallyWords $tally, Config $config) : TypedArray
	{
		$tally->scaleTally($config->window / 60.0); // changes value to count per minute

		//	Group words by normalized value.
		$normalizedGroup = [];
		foreach ($tally as $k => $v) {
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
		$normalizedGroup = array_slice($normalizedGroup, 0, $config->quantity);

		//	Sort on key.
		ksort($normalizedGroup, SORT_NATURAL | SORT_FLAG_CASE);

		$cloudWords = new TypedArray(Word::class);
		foreach ($normalizedGroup as &$group) {
			$totalTally = $group['_sum_'];
			unset($group['_sum_']);
			$groupKeys     = array_keys($group);
			$htmlTitle     = '';
			$twitterLookup = new Set();

			foreach ($group as $thisName => $thisTally) {
				$twitterLookup->add(strtolower($thisName));

				if (count($group) > 1) {
					$htmlTitle .= '<br>' . $thisName . ': ' . (string)$thisTally;
				}
			}

			$cloudWords[] = [
				'text'   => $groupKeys[0],
				'weight' => (int)((log($totalTally*5) * 40) + $totalTally*5),   //  A combination of log and linear.
				'link'   => 'javascript:ToTwitter(["' . implode('","', $twitterLookup->toArray()) . '"])',
				'html'   => [
					'title' => $totalTally . $htmlTitle,
					// 	'url' => 'https://twitter.com/search?f=tweets&vertical=news&q=%23' . implode('%20OR%20%23', $twitterLookup->toArray())
				],
			];
		}

		return $cloudWords;
	}

	/**
	 * Return quantity of each word in text field.
	 *
	 * @param Config     $config
	 *
	 * @return TypedArray
	 */
	public static function getText(Config $config) : TypedArray
	{
		$tweets = (new Tweets())->find([
			'created_at' => ['$gt' => new UTCDateTime(strtotime($config->window . ' seconds ago') * 1000)],
			'text'       => ['$gt' => ''],
		]);

		$tally = new TallyWords();
		foreach ($tweets as $tweet) {
//			if (preg_match('/(^039|^rt$)/i', $tweet['text'])) {
//				continue;
//			}

			$words = explode(' ', preg_replace('/[^0-9a-zA-Z\']+/', ' ', $tweet->text));

			foreach ($words as $word) {
				if ((strlen($word) < 3 && !is_numeric($word)) || in_array(strtolower($word), $config->stop)) {
					continue;
				}

				$tally->doTally($word);
			}
		}

		return self::_buildTagCloud($tally, $config);
	}

	private static function _sortCountSumDesc($a, $b)
	{
		if ($a['_sum_'] === $b['_sum_']) {
			return 0;
		}

		return ($a['_sum_'] > $b['_sum_']) ? -1 : 1;
	}

}
