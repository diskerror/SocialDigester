<?php

namespace Code\Tally;

use MongoDB\BSON\UTCDateTime;
use Phalcon\Config;
use Ds\Set;
use Diskerror\Typed\TypedArray;
use function preg_replace;
use Resource\Tallies;
use Structure\TallyWords;
use Resource\Tweets;

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
	public static function getHashtags(Config $config): TypedArray
	{
		$tweets = (new Tweets())->find([
			'created_at'               => ['$gte' => new UTCDateTime((time() - $config->window) * 1000)],
			'entities.hashtags.0.text' => ['$gt' => ''],
		]);

		$uniqueWords = new Set();
		$tally       = new TallyWords();
		foreach ($tweets as $tweet) {
			//	Make sure we have only one of a hashtag per tweet.
			$uniqueWords->clear();
			foreach ($tweet->entities->hashtags as $hashtag) {
				$uniqueWords->add($hashtag->text);
			}

			foreach ($uniqueWords as $uniqeWord) {
				$tally->doTally($uniqeWord);
			}
		}

		return self::_buildTagCloud($tally, $config->window, $config->quantity);
	}

	/**
	 * @param Config $config
	 *
	 * @return TypedArray
	 */
	public static function getHashtagsFromTallies(Config $config): TypedArray
	{
		$tallies = (new Tallies())->find([
			'created' => ['$gte' => new UTCDateTime((time() - $config->window) * 1000)],
		]);

		$totals = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->uniqueHashtags as $k => $v) {
				if ($totals->offsetExists($k)) {
					$totals[$k] += $v;
				}
				else {
					$totals[$k] = $v;
				}
			}
		}

		return self::_buildTagCloud($totals, $config->window, $config->quantity);
	}

	/**
	 * @param Config $config
	 *
	 * @return TypedArray
	 */
	public static function getAllHashtagsFromTallies(Config $config): TypedArray
	{
		$tallies = (new Tallies())->find([
			'created' => ['$gte' => new UTCDateTime((time() - $config->window) * 1000)],
		]);

		$totals = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->allHashtags as $k => $v) {
				if ($totals->offsetExists($k)) {
					$totals[$k] += $v;
				}
				else {
					$totals[$k] = $v;
				}
			}
		}

		return self::_buildTagCloud($totals, $config->window, $config->quantity);
	}

	/**
	 * Format data with TagCloud object.
	 * Words are normalized and grouped under the same tag.
	 *
	 * @param \Structure\TallyWords $tally
	 * @param Config                $config
	 *
	 * @return TypedArray
	 */
	private static function _buildTagCloud(TallyWords $tally, int $window, int $quantity, $technique = 'metaphone'): TypedArray
	{
		$tally->scaleTally($window / 60.0); // changes value to count per minute

		$normalizedGroups = self::_normalizeGroupsFromTally($tally, $quantity, $technique);

		//	Sort on key.
		ksort($normalizedGroups, SORT_NATURAL | SORT_FLAG_CASE);

		$cloudWords = new TypedArray(null, 'Structure\TagCloud\Word');
		foreach ($normalizedGroups as &$group) {
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
				'weight' => (int)((log($totalTally * 5) * 40) + $totalTally * 5),   //  A combination of log and linear.
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
	 * @param Config $config
	 *
	 * @return TypedArray
	 */
	public static function getText(Config $config): TypedArray
	{
		$tallies = (new Tallies())->find([
			'created' => ['$gte' => new UTCDateTime((time() - $config->window) * 1000)],
		]);

		$totals = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->textWords as $k => $v) {
				if ($totals->offsetExists($k)) {
					$totals[$k] += $v;
				}
				else {
					$totals[$k] = $v;
				}
			}
		}

		return self::_buildTagCloud($totals, $config->window, (int)($config->quantity * 1.5), 'strtolower');
	}

	/**
	 * @param Config $config
	 *
	 * @return TypedArray
	 */
	public static function getUserMentionsFromTallies(Config $config): TypedArray
	{
		$tallies = (new Tallies())->find([
			'created' => ['$gte' => new UTCDateTime((time() - $config->window) * 1000)],
		]);

		$totals = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->userMentions as $k => $v) {
				if ($totals->offsetExists($k)) {
					$totals[$k] += $v;
				}
				else {
					$totals[$k] = $v;
				}
			}
		}

		$tagCloud = self::_buildTagCloud($totals, $config->window, $config->quantity);
		foreach ($tagCloud as &$tc) {
			$tc->link = strtr($tc->link, ['javascript:ToTwitter(' => 'javascript:ToTwitterAt(']);
		}

		return $tagCloud;
	}

}
