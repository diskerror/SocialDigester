<?php

namespace Logic\Tally;

use MongoDB\BSON\UTCDateTime;
use Resource\Tallies;
use Structure\Config\Mongo;
use Structure\TallyWords;

final class HashtagsAll
{
	private function __construct() { }

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
				'projection' => ['allHashtags' => 1,],
			]
		);

		$totals = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->allHashtags as $k => $v) {
				$totals->doTally($k, $v);
			}
		}

		$totals->scaleTally($window / 60.0); // changes value to count per minute

		return $totals;
	}
}
