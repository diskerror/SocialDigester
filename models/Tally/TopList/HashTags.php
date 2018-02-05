<?php

namespace Tally\TopList;

use Diskerror;

class HashTags extends \Tally\AbstractTally
{
	/**
	 * Return count of each current hashtag.
	 *
	 * @param Phalcon\Config $config
	 *
	 * @return array
	 */
	function get($config)
	{
		$tweets = $this->_tweets->find([
			'entities.hashtags.0.text' => ['$gt' => ''],
			'created_at'               => ['$gt' => new \MongoDB\BSON\UTCDateTime(strtotime($config->window . ' seconds ago') * 1000)],
		]);

		foreach ($tweets as $tweet) {
			if (preg_match('/(^039|^rt$)/i', $tweet['text'])) {
				continue;
			}

			foreach ($tweet['entities']['hashtags'] as $hashtag) {
				$this->doTally(strtolower($hashtag['text']));
			}
		}

		$this->_rSortTally();

		return $this->_getTally();
	}

}
