<?php
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
	 * @param \MongoDB\Database $db
	 * @param Phalcon\Config    $config
	 *
	 * @return array
	 */
	public static function make(\MongoDB\Database $db, Phalcon\Config $config) : int
	{
		$snap = new Snapshot([
			'id_'      => time(),
			'track'    => implode(', ', (array)$config->twitter->track),
			'tagCloud' => Tally\TagCloud::getHashtags($db->tweets, $config->word_stats),
			'summary'  => Summary::get($db->tweets, $config->word_stats),
		]);
		$db->snapshots->insertOne($snap->getArrForMongo());
		return $snap->id_;
	}
}
