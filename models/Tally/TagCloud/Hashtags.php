<?php

namespace Tally\TagCloud;

use Ds\Set;

class Hashtags extends AbstractTagCloud
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
		$tweets = $this->_twit->find([
			'entities.hashtags.0.text' => ['$gt' => ''],
			'created_at'               => ['$gt' => new \MongoDB\BSON\UTCDateTime(strtotime($config->window . ' seconds ago') * 1000)],
		]);

		$uniqeWords = new Set();
		foreach ($tweets as $tweet) {
			if (preg_match('/(^039|^rt$)/i', $tweet['text'])) {
				continue;
			}

			//	Make sure we have only one of a hashtag per tweet.
			$uniqeWords->clear();
			foreach ($tweet['entities']['hashtags'] as $hashtag) {
				$uniqeWords->add($hashtag['text']);
			}

			foreach ($uniqeWords as $uniqeWord) {
				$this->doTally($uniqeWord);
			}
		}

		return $this->_buildTagCloud($config);
	}

}
