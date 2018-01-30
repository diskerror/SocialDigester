<?php

namespace Tally\TopList;

class Text extends \Tally\AbstractTally
{
	/**
	 * Return count of each word in text field.
	 *
	 * @param Phalcon\Config $config
	 *
	 * @return array
	 */
	function get($config)
	{
		$tweets = $this->_twit->find([
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

				$self->doTally(strtolower($word));
			}
		}

		arsort($this->_tally);

		return $this->_tally;
	}

}
