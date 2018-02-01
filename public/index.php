<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

define('APP_PATH', realpath(__DIR__ . '/..'));

require APP_PATH . '/functions/errorHandler.php';
require APP_PATH . '/vendor/autoload.php';

try {
	(new \Phalcon\Loader())
		->registerDirs([
			APP_PATH . '/controllers/',
			APP_PATH . '/models/',
			APP_PATH . '/structs/',
		])
		->register();

	require APP_PATH . '/functions/config.php';

	echo (new Phalcon\Mvc\Application(new Di($config)))
		->useImplicitView(false)
		->handle()
		->getContent();
}
catch (Throwable $t) {
	echo $t;
}
