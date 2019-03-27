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
	 * @param MongoCollectionManager $mongodb
	 * @param Config                 $config
	 *
	 * @return string
	 */
	public static function make(MongoCollectionManager $mongodb, Config $config): string
	{
		$snap = new Snapshot([
			'track'    => $config->track,
			'tagCloud' => TagCloud::getHashtags($mongodb, $config->word_stats),
			'summary'  => Summary::get($mongodb, $config->word_stats),
		]);

		$inserted = $mongodb->snapshots->insertOne($snap);
		return (string)$inserted->getInsertedId();
	}
}
