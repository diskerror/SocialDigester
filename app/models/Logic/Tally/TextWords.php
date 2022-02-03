<?php

namespace Logic\Tally;

use Logic\TallyInterface;
use MongoDB\BSON\UTCDateTime;
use Resource\MongoCollections\Tallies;
use Structure\Config\Mongo;
use Structure\StopWords;
use Structure\Tally;
use Structure\TallyWords;
use Structure\Tweet;

class TextWords implements TallyInterface
{
	private function __construct() { }

	public static function pre(Tweet $tweet, Tally &$tally)
	{
		//	Tally the words in the text.
		$split = preg_split('/[^a-zA-Z0-9_\']/', $tweet->text, null, PREG_SPLIT_NO_EMPTY);
		foreach ($split as $s) {
			if (strlen($s) > 2 && !StopWords::contains(strtolower($s))) {
				$tally->textWords->doTally($s);
			}
		}
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
				'projection' => ['textWords' => 1,],
			]
		);

		$totals = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->textWords as $k => $v) {
				$totals->doTally($k, $v);
			}
		}

		$totals->scaleTally($window / 60.0); // changes value to count per minute

		return $totals;
	}
}
