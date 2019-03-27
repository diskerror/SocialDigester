<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/26/18
 * Time: 2:22 PM
 */

namespace Code\Tally;

use Code\TallyWords;
use Resource\MongoCollectionManager;
use Structure\Config\WordStats;
use function strtolower;

final class TopList
{
	use TallyTrait;

	/**
	 * Return quantity of each current hashtag.
	 *
	 * @param MongoCollectionManager $mongodb
	 * @param WordStats              $config
	 *
	 * @return TallyWords
	 */
	public static function getHashtags(MongoCollectionManager $mongodb, WordStats $config): TallyWords
	{
		$tweets = $mongodb->tweets->find([
			'created_at'               => ['$gt' => self::_getWindowDate($config->window)],
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

		/**
		 * Scales the count per hashtag to the count per minute.
		 */
		$tally->scaleTally($config->window/60.0);

		return $tally;
	}

	/**
	 * Return quantity of each word in text field.
	 *
	 * @param MongoCollectionManager $mongodb
	 * @param WordStats              $config
	 *
	 * @return TallyWords
	 */
	public static function getText(MongoCollectionManager $mongodb, WordStats $config): TallyWords
	{
		$tweets = $mongodb->tweets->find([
			'created_at' => ['$gt' => self::_getWindowDate($config->window)],
			'text'       => ['$gt' => ''],
		]);

		$stopWords = $config->stopWords->toArray();

		$tally = new TallyWords();
		foreach ($tweets as $tweet) {
//			if (preg_match('/(^039|^rt$)/i', $tweet['text'])) {
//				continue;
//			}

			$words = preg_split('/[^0-9a-zA-Z\']+/', $tweet->text);
			foreach ($words as $word) {
				$word = strtolower($word);
				$ln   = strlen($word);

				if (
					($ln < 3 && !is_numeric($word)) ||
					in_array($word, $stopWords)
				) {
					continue;
				}

				$tally->doTally($word);
			}
		}

		$tally->sort();

		/**
		 * Scales the count per word to the count per minute.
		 */
		$tally->scaleTally($config->window/60.0);

		return $tally;
	}

}
