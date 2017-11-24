<?php

class GetData
{

	/**
	 * Return count of each current hashtag.
	 *
	 * @param int $howMany
	 * @return array
	 */
	static function hashtags($howMany=120)
	{
		$mongo = new MongoDB\Client( Diskerror\Utilities\Registry::get('mongo') );
		$twit = $mongo->feed->twitter;

		$hashtags = $twit->find([
			'hashtags' => ['$gt' => ''],
			'created_at' => ['$gt' => new \MongoDB\BSON\UTCDateTime( strtotime('300 seconds ago')*1000 )]
		], [
			'projection' => ['hashtags' => 1]
		]);

		$tally = [];
		$normTally = [];	//	tally of normalized words
		foreach ( $hashtags as $ht ) {
			foreach ( $ht['hashtags'] as $h ) {
				if ( preg_match('/(^039|^rt$)/i', $h) ) {
					continue;
				}

				if ( array_key_exists( $h, $tally ) ) {
					++$tally[$h];
				}
				else {
					$tally[$h] = 1;
				}

				$h = strtolower($h);
				if ( array_key_exists( $h, $normTally ) ) {
					++$normTally[$h];
				}
				else {
					$normTally[$h] = 1;
				}
			}
		}

		//	match the typed hashtags to the normalized hashtags
		$properName = [];
		foreach ( $tally as $k=>$v ) {
			$properName[strtolower($k)][$k] = $v;
		}

		//	find the most used capitalization style for the hashtag
		foreach ( $properName as $k=>$v ) {
			$name = '';
			$maxTally = 0;

			foreach ( $v as $origName=>$count ) {
				if ( $count > $maxTally ) {
					$name = $origName;
					$maxTally = $count;
				}
			}

			$properName[$k] = $name;
		}

		arsort($normTally);
		$normTally = array_slice($normTally, 0, $howMany);
		ksort($normTally, SORT_NATURAL | SORT_FLAG_CASE);

		$count = 0;
		$ret = new Diskerror\Typed\TypedArray(null, 'TagCloudWord');
		foreach ( $normTally as $k=>$v ) {
			$ret[$count] = [
				'text' => $properName[$k],
				'weight' => ($v>300 ? 300 : $v),
				'link' => 'javascript:ToTwitter("' . $k . '")',
				'html' => [
					'title' => $v
				]
			];

			$count++;
		}

		return $ret->getSpecialObj(['dateToBsonDate'=>false]);
	}

}
