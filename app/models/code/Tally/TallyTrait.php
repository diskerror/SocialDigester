<?php

namespace Code\Tally;

use MongoDB\BSON\UTCDateTime;

trait TallyTrait
{
	/**
	 * Normalize test for a more meaningful tally.
	 *
	 * Intl UTF-8 characters have already been normalized to ASCII.
	 * Non-latin characters were left alone.
	 *
	 * @param string $s
	 *
	 * @return string
	 */
	protected static function _normalizeText(string $s): string
	{
		//	remove trailing digits
		$s = preg_replace('/\d+$/', '', $s);

		//	So "Schumer" == "Shumer".
		$s = preg_replace('/sch/i', 'sh', $s);

		//	Plural becomes singular for longer words.
//		if (strlen($s) > 5) {
//			$s = preg_replace('/s+$/i', '', $s);
//		}

// 		return strtolower($s);
		return metaphone($s);
// 		return soundex($s);
// 		return \Diskerror\stem($s)[0];
	}

	protected static function _getWindowDate(int $seconds): UTCDateTime
	{
		return new UTCDateTime(strtotime($seconds . ' seconds ago') * 1000);
	}

}
