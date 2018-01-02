<?php

class GetTally
{
	protected $_twit;
	protected $_tally;

	/**
	 * @param MongoDB\Client $mongo
	 */
	function __construct(MongoDB\Client $mongo)
	{
		$this->_twit = $mongo->feed->twitter;
		$this->_tally = [];
	}

	protected static function _doTally(array &$tally, $word)
	{
		if ( array_key_exists( $word, $tally ) ) {
			++$tally[$word];
		}
		else {
			$tally[$word] = 1;
		}
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

		$tally = [];		//	tally of hashtags as written
		$normTally = [];	//	tally of normalized hashtags, all lower case
		foreach ( $tweets as $tweet ) {
			if ( preg_match('/(^039|^rt$)/i', $tweet['text']) ) {
				continue;
			}

			foreach ( $tweet['entities']['hashtags'] as $h ) {
				$t = $h['text'];

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

		return $this->_buildTagCloud($tally, $normTally, $config, 300);
	}

	/**
	 * Return count of each word in text field.
	 *
	 * @param Phalcon\Config $config
	 * @return array
	 */
	function words($config)
	{
		$tweets = $this->_twit->find([
			'text' => ['$gt' => ''],
			'created_at' => ['$gt' => new \MongoDB\BSON\UTCDateTime( strtotime($config->window . ' seconds ago')*1000 )]
		]);

		$tally = [];		//	tally of words as written
		$normTally = [];	//	tally of normalized words, all lower case
		foreach ( $tweets as $tweet ) {
			if ( preg_match('/(^039|^rt$)/i', $tweet['text']) ) {
				continue;
			}

			$words = explode(' ', preg_replace('/[^0-9a-zA-Z\']+/', ' ', $tweet->text));

			foreach ( $words as $word ) {
				if ( ( strlen($word) < 3 && !is_numeric($word) ) || in_array(strtolower($word), $config->stop) ) {
					continue;
				}

				if ( array_key_exists( $word, $tally ) ) {
					++$tally[$word];
				}
				else {
					$tally[$word] = 1;
				}

				$word = strtolower($word);
				if ( array_key_exists( $word, $normTally ) ) {
					++$normTally[$word];
				}
				else {
					$normTally[$word] = 1;
				}
			}
		}

		return $this->_buildTagCloud($tally, $normTally, $config, 800);
	}

	/**
	 * Format data with TagCloud object.
	 *
	 * @param array $tally
	 * @param array $normTally
	 * @param Phalcon\Config $config
	 * @param int $weightLimit
	 * @return array
	 */
	protected function _buildTagCloud($tally, $normTally, $config, $weightLimit)
	{
		//	match the as-written words to the normalized words
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
				'weight' => ($v>$weightLimit ? $weightLimit : $v),
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
