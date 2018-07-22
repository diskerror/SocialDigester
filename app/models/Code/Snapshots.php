<?php

namespace Code;

/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/27/18
 * Time: 11:44 AM
 */
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
			'id_'      => time(),
			'track'    => (array)$config->twitter->track,
			'tagCloud' => Tally\TagCloud::getHashtags($config->word_stats),
			'summary'  => Summary::get($config->word_stats),
		]);
		(new \Resource\Snapshots())->insertOne($snap);
		return $snap->id_;
	}
}
