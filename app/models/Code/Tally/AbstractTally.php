<?php

namespace Code\Tally;

abstract class AbstractTally
{
	private function __construct() { }

	protected static function _normalizeText($s)
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

}
