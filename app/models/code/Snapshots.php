<?php

namespace Code;

final class Snapshots
{
	private function __construct() { }

	/**
	 * Grab and save the current state of data.
	 *
	 * @param \Phalcon\Config $config
	 *
	 * @return array
	 */
	public static function make(\Phalcon\Config $config) : int
	{
		$snap = new \Structure\Snapshot([
			'_id'      => time(),
			'track'    => (array)$config->twitter->track,
			'tagCloud' => Tally\TagCloud::getHashtags($config->word_stats),
			'summary'  => Summary::get($config->word_stats),
		]);
		(new \Resource\Snapshots())->insertOne($snap);
		return $snap->_id;
	}
}
