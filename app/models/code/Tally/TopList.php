<?php

namespace Code\Tally;

use Ds\Set;
use MongoDB\BSON\UTCDateTime;
use Phalcon\Config;
use Resource\Tallies;
use Resource\Tweets;
use Structure\TallyWords;

final class TopList extends AbstractTally
{
	private function __construct() { }

	/**
	 * Return quantity of each current hashtag.
	 *
	 * @param Config $config
	 *
	 * @return \Structure\TallyWords
	 */
	public static function getHashtags(Config $config): array
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

		$tally->sort();
		$tally->scaleTally($config->window / 60.0);

		$normalized = self::_normalizeGroupsFromTally($tally, $config->quantity);

		$output = [];
		foreach ($normalized as $n) {
			$output[array_keys($n)[0]] = round($n['_sum_'], 2);
		}

		return $output;
	}

	public static function getHashtagsFromTallies(Config $config): array
	{
		$tallies = (new Tallies())->find([
			'created' => ['$gte' => new UTCDateTime((time() - $config->window) * 1000)],
		]);

		$totals = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->hashtags as $k => $v) {
				if ($totals->offsetExists($k)) {
					$totals[$k] += $v;
				}
				else {
					$totals[$k] = $v;
				}
			}
		}

		$totals->sort();
		$totals->scaleTally($config->window / 60.0);

		$normalized = self::_normalizeGroupsFromTally($totals, $config->quantity);

		$output = [];
		foreach ($normalized as $n) {
			$output[array_keys($n)[0]] = round($n['_sum_'], 2);
		}

		return $output;
	}

	/**
	 * Return quantity of each word in text field.
	 *
	 * @param Config $config
	 *
	 * @return \Structure\TallyWords
	 */
	public static function getText(Config $config): TallyWords
	{
		$tweets = (new Tweets())->find([
			'created_at' => ['$gte' => new UTCDateTime((time() - $config->window) * 1000)],
			'text'       => ['$gt' => ''],
		]);

		$tally = new TallyWords();
		foreach ($tweets as $tweet) {
			$words = preg_split('/([^0-9a-zA-Z\']| )+/', $tweet->text);
			foreach ($words as $word) {
				if ((strlen($word) < 3 && !is_numeric($word)) || in_array(strtolower($word), (array)$config->stop,
						true)) {
					continue;
				}

				$tally->doTally(strtolower($word));
			}
		}

		$tally->sort();
		$tally->scaleTally(60);

		return $tally;
	}

}
