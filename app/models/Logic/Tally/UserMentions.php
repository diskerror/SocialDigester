<?php

namespace Logic\Tally;

use Logic\TallyInterface;
use MongoDB\BSON\UTCDateTime;
use Resource\MongoCollections\Tallies;
use Structure\Config\Mongo;
use Structure\Tally;
use Structure\TallyWords;
use Structure\Tweet;

class UserMentions implements TallyInterface
{
	private function __construct() { }

	public static function pre(Tweet $tweet, Tally &$tally)
	{
		//	Tally user mentions.
		//	Make sure they are not referring to themselves.
		foreach ($tweet->entities->user_mentions as $userMention) {
			$sn = $userMention->screen_name;
			if ($sn !== $tweet->user->screen_name) {
				$tally->userMentions->doTally($userMention->screen_name);
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
