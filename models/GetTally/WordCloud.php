<?php

namespace GetTally;

class WordCloud extends GetTally
{
	protected $_normTally;

	/**
	 * @param MongoDB\Client $mongo
	 */
	function __construct(MongoDB\Client $mongo)
	{
		self::parent($mongo);
		$this->_normTally = [];
	}

	/**
	 * Format data with TagCloud object.
	 *
	 * @param Phalcon\Config $config
	 * @param int $weightLimit
	 * @return array
	 */
	protected function _buildTagCloud($config, $weightLimit)
	{
		//	match the as-written words to the normalized words
		$properName = [];
		foreach ( $this->_tally as $k=>$v ) {
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

		arsort($this->_normTally);
		$this->_normTally = array_slice($this->_normTally, 0, $config->count);
		ksort($this->_normTally, SORT_NATURAL | SORT_FLAG_CASE);

		$count = 0;
		$ret = new Diskerror\Typed\TypedArray(null, 'TagCloudWord');
		foreach ( $this->_normTally as $k=>$v ) {
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
