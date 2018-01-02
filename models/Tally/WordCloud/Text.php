<?php

namespace Tally\WordCloud;

class Text extends AbstractWordCloud
{
	/**
	 * Return count of each word in text field.
	 *
	 * @param Phalcon\Config $config
	 * @return array
	 */
	function get($config)
	{
		$tweets = $this->_twit->find([
			'text' => ['$gt' => ''],
			'created_at' => ['$gt' => new \MongoDB\BSON\UTCDateTime( strtotime($config->window . ' seconds ago')*1000 )]
		]);

		foreach ( $tweets as $tweet ) {
			if ( preg_match('/(^039|^rt$)/i', $tweet['text']) ) {
				continue;
			}

			$words = explode(' ', preg_replace('/[^0-9a-zA-Z\']+/', ' ', $tweet->text));

			foreach ( $words as $word ) {
				if ( ( strlen($word) < 3 && !is_numeric($word) ) || in_array(strtolower($word), $config->stop) ) {
					continue;
				}

				self::doTally($word, $this->_tally);
				self::doTally(strtolower($word), $this->_normTally);
			}
		}

		return $this->_buildTagCloud($config, 800);
	}

}
