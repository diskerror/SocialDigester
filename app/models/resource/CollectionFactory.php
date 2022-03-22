<?php

namespace Resource;

use Structure\CollectionDef;
use Structure\Config;

class CollectionFactory
{
	public static function tweets(Config $config)
	{
		static $collection;
		if (!isset($collection)) {
			$collection = new MongoCollection($config->mongo_db, require $config->configPath . '/TweetsColl.php');
		}

		return $collection;
	}

	public static function tallies(Config $config)
	{
		static $collection;
		if (!isset($collection)) {
			$collection = new MongoCollection($config->mongo_db, require $config->configPath . '/TalliesColl.php');
		}

		return $collection;
	}

	public static function messages(Config $config)
	{
		static $collection;
		if (!isset($collection)) {
			$collection = new MongoCollection($config->mongo_db, require $config->configPath . '/MessagesColl.php');
		}

		return $collection;
	}

	public static function snapshots(Config $config)
	{
		static $collection;
		if (!isset($collection)) {
			$collection = new MongoCollection($config->mongo_db, require $config->configPath . '/SnapshotsColl.php');
		}

		return $collection;
	}

}
