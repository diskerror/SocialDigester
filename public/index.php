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
	$di = new Di\Web($config);

	$di->setShared('view', function() {
		static $view;
		if (!isset($view)) {
			$view = new Phalcon\Mvc\View\Simple();
			$view->setViewsDir(APP_PATH . '/views/');
		}
		return $view;
	});

// $di->setShared('url', function () use ($config) {
//	   $url = new Phalcon\Mvc\Url();
//	   $url->setBaseUri($config->application->baseUri);
//	   return $url;
// });

	echo (new Phalcon\Mvc\Application($di))
		->useImplicitView(false)
		->handle()
		->getContent();
}
catch (Throwable $t) {
	echo $t;
}
