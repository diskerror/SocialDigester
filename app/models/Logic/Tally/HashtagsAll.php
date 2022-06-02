<?php

namespace Logic\Tally;

use Logic\TallyInterface;
use LogicException;
use MongoDB\BSON\UTCDateTime;
use Resource\CollectionFactory;
use Structure\Config;
use Structure\Tally;
use Structure\TallyWords;
use Structure\Tweet;

final class HashtagsAll implements TallyInterface
{
	private function __construct() { }

	public static function pre(Tweet $tweet, Tally $tally): void
	{
		throw new LogicException('This data set is built in the Hashtags class.');
	}

	/**
	 * @param Config $config
	 * @param int    $window
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
