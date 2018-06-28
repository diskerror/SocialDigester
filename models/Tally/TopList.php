<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/26/18
 * Time: 2:22 PM
 */

namespace Tally;

use TallyWords;

final class TopList extends AbstractTally
{
	private function __construct() { }

	/**
	 * Return count of each current hashtag.
	 *
	 * @param \MongoDB\Collection $tweetsCollection
	 * @param Phalcon\Config      $config
	 *
	 * @return TallyWords
	 */
	public static function getHashtags(\MongoDB\Collection $tweetsCollection, \Phalcon\Config $config) : TallyWords
	{
		$tweets = $tweetsCollection->find([
			'entities.hashtags.0.text' => ['$gt' => ''],
			'created_at'               => ['$gt' => new \MongoDB\BSON\UTCDateTime(strtotime($config->window . ' seconds ago') * 1000)],
		]);

		$tally = new TallyWords();
		foreach ($tweets as $tweet) {
//			if (preg_match('/(^039|^rt$)/i', $tweet['text'])) {
//				continue;
//			}

			foreach ($tweet['entities']['hashtags'] as $hashtag) {
				$tally->doTally(strtolower($hashtag['text']));
			}
		}

		$tally->sort();

		return $tally;
	}

	/**
	 * Return count of each word in text field.
	 *
	 * @param \MongoDB\Collection $tweetsCollection
	 * @param Phalcon\Config      $config
	 *
	 * @return TallyWords
	 */
	public static function getText(\MongoDB\Collection $tweetsCollection, $config) : TallyWords
	{
		$tweets = $tweetsCollection->find([
			'text'       => ['$gt' => ''],
			'created_at' => ['$gt' => new \MongoDB\BSON\UTCDateTime(strtotime($config->window . ' seconds ago') * 1000)],
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

				$tally->doTally(strtolower($word));
			}
		}

		$tally->sort();

		return $tally;
	}

}
