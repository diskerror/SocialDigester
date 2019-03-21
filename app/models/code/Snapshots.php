<?php

namespace Code;

use Code\Tally\TagCloud;
use Resource\MongoCollectionManager;
use Structure\Config;
use Structure\Snapshot;

final class Snapshots
{
	private function __construct() { }

	/**
	 * Grab and save the current state of data.
	 *
	 * @param Config                 $config
	 * @param MongoCollectionManager $mongodb
	 *
	 * @return string
	 */
	public static function make(Config $config, MongoCollectionManager $mongodb): string
	{
		$snap = new Snapshot([
			'track'    => $config->twitter->track,
			'tagCloud' => TagCloud::getHashtags($config->word_stats),
			'summary'  => Summary::get($config->word_stats),
		]);

		$result = $mongodb->snapshots->insertOne($snap);
		return (string) $result->getInsertedId();
	}
}
