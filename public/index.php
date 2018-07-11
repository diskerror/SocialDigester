<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {
	require APP_PATH . '/functions/errorHandler.php';
	require BASE_PATH . '/vendor/autoload.php';

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
