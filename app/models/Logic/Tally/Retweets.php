<?php

namespace Logic\Tally;

use Logic\TallyInterface;
use MongoDB\BSON\UTCDateTime;
use Resource\CollectionFactory;
use Structure\Config;
use Structure\Tally;
use Structure\TallyWords;
use Structure\Tweet;

class Retweets implements TallyInterface
{
	private function __construct() { }

	public static function pre(Tweet $tweet, Tally $tally): void
	{
		//	Tally retweeted users but not if they retweet themselves.
		$rtName = $tweet->retweeted_status->user->screen_name;
		if ($rtName !== $tweet->user->screen_name) {
			$tally->retweets->doTally($rtName);
		}
	}

	/**
	 * @param Config $config
	 * @param int $window
	 *
	 * @return TallyWords
	 */
	public static function get(Config $config, int $window): TallyWords
	{
		$tallies = CollectionFactory::tallies($config)->find(
			[
				'created' => ['$gte' => new UTCDateTime((time() - $window) * 1000)],
			],
			[
				'projection' => ['retweets' => 1,],
			]
		);

		$totals = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->retweets as $k => $v) {
				$totals->doTally($k, $v);
			}
		}

		return $totals;
	}
}
