<?php

namespace Logic\Tally;

use Logic\TallyInterface;
use MongoDB\BSON\UTCDateTime;
use Resource\CollectionFactory;
use Structure\Config;
use Structure\Tally;
use Structure\TallyWords;
use Structure\Tweet;

class TextWords implements TallyInterface
{
	private function __construct() { }

	public static function pre(Tweet $tweet, Tally $tally): void
	{
		//	Tally the words in the text.
		$split = preg_split('/[^a-zA-Z0-9_\']/', $tweet->text, null, PREG_SPLIT_NO_EMPTY);
//		$words = [];
//		foreach ($split as $s) {
//			if (strlen($s) > 2 && !StopWords::contains(strtolower($s))) {
//				$words[] = $s;
//			}
//		}
		$tally->textWords->countArrayValues($split);
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
				'projection' => ['textWords' => 1,],
			]
		);

		$stopWords = require $config->configPath . '/StopWords.php';
		$totals    = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->textWords as $k => $v) {
				if (!$stopWords->contains(strtolower($k))) {
					$totals->doTally($k, $v);
				}
			}
		}

		$totals->scaleTally($window / 60.0); // changes value to count per minute

		return $totals;
	}
}
