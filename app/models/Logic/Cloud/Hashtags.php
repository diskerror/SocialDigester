<?php

namespace Logic\Cloud;

use Diskerror\Typed\TypedArray;
use MongoDB\BSON\UTCDateTime;
use Resource\Tallies;
use Structure\Config;
use Structure\TallyWords;

final class Hashtags extends AbstractCloud
{
	const WINDOW   = 60;
	const QUANTITY = 32;

	private function __construct() { }


	/**
	 * @param Config $config
	 *
	 * @return TypedArray
	 */
	public static function get(Config $config): TypedArray
	{
//		$tallies = (new Tallies($config->mongo_db))->find([
//			'created' => ['$gte' => new UTCDateTime((time() - $config->word_stats->window) * 1000)],
//		]);
		$tallies = (new Tallies($config->mongo_db))->find([
			'created' => ['$gte' => new UTCDateTime((time() - self::WINDOW) * 1000)],
		]);

		$totals = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->uniqueHashtags as $k => $v) {
				$totals->doTally($k, $v);
			}
		}

		return self::_buildTagCloud($totals, self::WINDOW, self::QUANTITY);
	}
}
