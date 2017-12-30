<?php

class GetData
{
	protected $_twit;

	/**
	 * @param MongoDB\Client $mongo
	 */
	function __construct(MongoDB\Client $mongo)
	{
		$this->_twit = $mongo->feed->twitter;
	}

	/**
	 * Return count of each current hashtag.
	 *
	 * @param Phalcon\Config $config
	 * @return array
	 */
	function hashtags($config)
	{
		$tweets = $this->_twit->find([
			'entities.hashtags.0.text' => ['$gt' => ''],
			'created_at' => ['$gt' => new \MongoDB\BSON\UTCDateTime( strtotime($config->window . ' seconds ago')*1000 )]
		]);

		$tally = [];
		$normTally = [];	//	tally of normalized words
		foreach ( $tweets as $tweet ) {
			foreach ( $tweet['entities']['hashtags'] as $h ) {
				$t = $h['text'];
				if ( preg_match('/(^039|^rt$)/i', $t) ) {
					continue;
				}

				if ( array_key_exists( $t, $tally ) ) {
					++$tally[$t];
				}
				else {
					$tally[$t] = 1;
				}

				$t = strtolower($t);
				if ( array_key_exists( $t, $normTally ) ) {
					++$normTally[$t];
				}
				else {
					$normTally[$t] = 1;
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
		$normTally = array_slice($normTally, 0, $config->count);
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
