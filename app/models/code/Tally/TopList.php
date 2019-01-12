<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/26/18
 * Time: 2:22 PM
 */

namespace Code\Tally;

use Code\TallyWords;
use Resource\Tweets;
use Tally\Phalcon;

final class TopList extends AbstractTally
{
	private function __construct() { }

	/**
	 * Return quantity of each current hashtag.
	 *
	 * @param Phalcon\Config $config
	 *
	 * @return TallyWords
	 */
	public static function getHashtags(\Phalcon\Config $config) : TallyWords
	{
		$tweets = (new Tweets())->find([
			'created_at'               =>
				['$gt' => new \MongoDB\BSON\UTCDateTime(strtotime($config->window . ' seconds ago') * 1000)],
			'entities.hashtags.0.text' => ['$gt' => ''],
		]);

		$tally = new TallyWords();
		foreach ($tweets as $tweet) {
//			if (preg_match('/(^039|^rt$)/i', $tweet->text)) {
//				continue;
//			}

			foreach ($tweet->entities->hashtags as $hashtag) {
				$tally->doTally(strtolower($hashtag->text));
			}
		}

		$tally->sort();
		$tally->scaleTally(60);

		return $tally;
	}

	/**
	 * Return quantity of each word in text field.
	 *
	 * @param Phalcon\Config $config
	 *
	 * @return TallyWords
	 */
	public static function getText(\Phalcon\Config $config) : TallyWords
	{
		$tweets = (new Tweets())->find([
			'created_at' => ['$gt' => new \MongoDB\BSON\UTCDateTime( strtotime($config->window . ' seconds ago') * 1000)],
			'text'       => ['$gt' => ''],
		]);

		$tally = new TallyWords();
		foreach ($tweets as $tweet) {
//			if (preg_match('/(^039|^rt$)/i', $tweet['text'])) {
//				continue;
//			}

			$words = preg_split('/([^0-9a-zA-Z\']| )+/', $tweet->text);
			foreach ($words as $word) {
				if ((strlen($word) < 3 && !is_numeric($word)) || in_array(strtolower($word), (array)$config->stop)) {
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
