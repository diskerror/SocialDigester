<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View\Simple;

ini_set('display_errors', '1');
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

/**
 * Convert all errors into ErrorExceptions
 */
set_error_handler(
	function($severity, $errstr, $errfile, $errline) {
		throw new ErrorException($errstr, 1, $severity, $errfile, $errline);
	},
	E_ERROR
);

try {
	//	Models are loaded with the Composer autoloader.
	require BASE_PATH . '/vendor/autoload.php';

	(new Loader())
		->registerDirs([
			APP_PATH . '/controllers/',
		])
		->register();

	$di = new FactoryDefault();

	require APP_PATH . '/DiCommon.inc';

	$di->setShared('view', function() {
		static $view;
		if (!isset($view)) {
			$view = new Simple();
			$view->setViewsDir(APP_PATH . '/views/');
		}
		return $view;
	});

//	$di->setShared('url', function() {
//		$url = new Phalcon\Mvc\Url();
//		$url->setBaseUri($this->getConfig()->application->baseUri);
//		return $url;
//	});

//	$di->setShared('dispatcher', function() {
//		$events = $this->getShared('eventsManager');
//
//		$events->attach(
//			'dispatch:beforeException',
//			function($event, $dispatcher, $exception) {
//				switch ($exception->getCode()) {
//					case Phalcon\Mvc\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
//					case Phalcon\Mvc\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
//						$dispatcher->forward([
//							'controller' => 'error',
//							'action'     => 'show404',
//						]);
//						return false;
//				}
//			}
//		);
//
//		$dispatcher = new Phalcon\Mvc\Dispatcher();
//		$dispatcher->setEventsManager($events);
//		return $dispatcher;
//	});

	echo (new Application($di))
		->useImplicitView(false)
		->handle($_SERVER["REQUEST_URI"])
		->getContent();
}
catch (Throwable $t) {
	echo $t;
}
