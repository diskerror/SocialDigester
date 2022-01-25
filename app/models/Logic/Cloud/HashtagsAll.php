<?php

namespace Logic\Cloud;

use Diskerror\Typed\TypedArray;
use MongoDB\BSON\UTCDateTime;
use Resource\Tallies;
use Structure\Config;
use Structure\TallyWords;

final class HashtagsAll extends AbstractCloud
{
	const WINDOW   = 180;
	const QUANTITY = 32;

	/**
	 * @param Config $config
	 *
	 * @return TypedArray
	 */
	public static function get(Config $config): TypedArray
	{
		$tallies = (new Tallies($config->mongo_db))->find([
			'created' => ['$gte' => new UTCDateTime((time() - self::WINDOW) * 1000)],
		]);

		$totals = new TallyWords();
		foreach ($tallies as $tally) {
			foreach ($tally->allHashtags as $k => $v) {
				$totals->doTally($k, $v);
			}
		}

		return self::_buildTagCloud($totals, self::WINDOW, self::QUANTITY);
	}
}
