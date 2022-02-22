<?php

namespace Logic\Tally;

use Ds\Set;
use Logic\TallyInterface;
use MongoDB\BSON\UTCDateTime;
use Resource\MongoCollections\Tallies;
use Structure\Config\Mongo;
use Structure\Tally;
use Structure\TallyWords;
use Structure\Tweet;

/**
 *
 */
final class Hashtags implements TallyInterface
{
	/**
	 *
	 */
	private function __construct() { }

	/**
	 * @param Tweet $tweet
	 * @param Tally $tally
	 * @return mixed|void
	 */
	public static function pre(Tweet $tweet, Tally &$tally)
	{
		//	Skip hashtag with non-latin scripts.
		$uniqueHashtags = new Set();
		foreach ($tweet->entities->hashtags as $hashtag) {
			if (mb_ord(mb_substr($hashtag->text, 0, 1)) > 592 || $hashtag->text === '') {
				continue;
			}

			//	Make sure we have only one of a hashtag per tweet for uniqueHashtags.
			$uniqueHashtags->add($hashtag->text);
			$tally->allHashtags->doTally($hashtag->text);
		}

		//	Count unique hashtags for this tweet.
		$tally->uniqueHashtags->countArrayValues($uniqueHashtags->toArray());
	}

	/**
	 * @param Mongo $mongo_db
	 * @param int $window
	 *
	 * @return TallyWords
	 */
	public static function get(Mongo $mongo_db, int $window): TallyWords
	{
		$tallies = (new Tallies($mongo_db))->find(
			[
				'created' => ['$gte' => new UTCDateTime((time() - $window) * 1000)],
			],
			[
				'projection' => ['uniqueHashtags' => 1,],
			]
		);

		$totals = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->uniqueHashtags as $k => $v) {
				$totals->doTally($k, $v);
			}
		}

		$totals->scaleTally($window / 60.0); // changes value to count per minute

		return $totals;
	}
}
