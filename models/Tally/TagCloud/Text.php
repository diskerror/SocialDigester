<?php

namespace Tally\TagCloud;

class Text extends AbstractTagCloud
{
	/**
	 * Return count of each word in text field.
	 *
	 * @param Phalcon\Config $config
	 *
	 * @return array
	 */
	function get(\Phalcon\Config $config)
	{
		$tweets = $this->_tweets->find([
			'text'       => ['$gt' => ''],
			'created_at' => ['$gt' => new \MongoDB\BSON\UTCDateTime(strtotime($config->window . ' seconds ago') * 1000)],
		]);

		foreach ($tweets as $tweet) {
			if (preg_match('/(^039|^rt$)/i', $tweet['text'])) {
				continue;
			}

			$words = explode(' ', preg_replace('/[^0-9a-zA-Z\']+/', ' ', $tweet->text));

			foreach ($words as $word) {
				if ((strlen($word) < 3 && !is_numeric($word)) || in_array(strtolower($word), $config->stop)) {
					continue;
				}

				$this->doTally($word);
			}
		}

		return $this->_buildTagCloud($config);
	}

}
