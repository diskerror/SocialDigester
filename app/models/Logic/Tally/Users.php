<?php

namespace Logic\Tally;

use Logic\TallyInterface;
use MongoDB\BSON\UTCDateTime;
use Resource\MongoCollection;
use Structure\Config;
use Structure\Tally;
use Structure\TallyWords;
use Structure\Tweet;

class Users implements TallyInterface
{
	private function __construct() { }

	public static function pre(Tweet $tweet, Tally $tally): void
	{
		//	Tally users.
		$tally->users->doTally($tweet->user->screen_name);
	}

	/**
	 * @param Config $config
	 * @param int    $window
	 *
	 * @return TallyWords
	 */
	public static function get(Config $config, int $window): TallyWords
	{
		$tallies = (new MongoCollection($config, 'tallies'))->find(
			[
				'created' => ['$gte' => new UTCDateTime((time() - $window) * 1000)],
			],
			[
				'projection' => ['users' => 1,],
			]
		);

		$totals = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->users as $k => $v) {
				$totals->doTally($k, $v);
			}
		}

		return $totals;
	}
}
