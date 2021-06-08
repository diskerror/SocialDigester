<?php
/**
 * This file contains the common bootstrap Code.
 */

$di->setShared('config', function() {
	static $config;
	if (!isset($config)) {
		//	Always open this configuration file with it's default values.
		$configFile = APP_PATH . '/config/config.php';
		$config     = new Phalcon\Config(require $configFile);

		//	Open all other files ending with '.php' as a configuration file.
		//	'glob' defaults to sorted.
		foreach (glob(APP_PATH . '/config/*.php') as $g) {
			if ($g !== $configFile && !is_dir($g)) {
				$config->merge(new Phalcon\Config(require $g));
			}
		}
	}

	return $config;
});

$di->setShared('mongo', function() {
	static $mongo;
	if (!isset($mongo)) {
		$mongo_db = $this->getConfig()->mongo_db;
		$client = new MongoDB\Client($mongo_db->host);
		$mongo  = $client->{$mongo_db->database};
	}
	return $mongo;
});

$di->set('collectionManager', function() {
	return new Phalcon\Mvc\Collection\Manager();
}, true);
