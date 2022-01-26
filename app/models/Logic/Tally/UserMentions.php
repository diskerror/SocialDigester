<?php

namespace Logic\Tally;

use MongoDB\BSON\UTCDateTime;
use Resource\Tallies;
use Structure\Config\Mongo;
use Structure\TallyWords;

class UserMentions
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
				'projection' => ['userMentions' => 1,],
			]
		);

		$totals = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->userMentions as $k => $v) {
				$totals->doTally($k, $v);
			}
		}

		$totals->scaleTally($window / 60.0); // changes value to count per minute

		return $totals;
	}

	public static function changeLink(&$cloud): void
	{
		foreach ($cloud as &$c) {
			$c->link = strtr($c->link, ['javascript:ToTwitter(' => 'javascript:ToTwitterAt(']);
		}
	}

}
