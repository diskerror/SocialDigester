<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));

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

	(new \Phalcon\Loader())
		->registerDirs([
			BASE_PATH . '/app/controllers/',
		])
		->register();

	$di = new \Phalcon\Di\FactoryDefault();

	require BASE_PATH . '/app/DiCommon.inc';

	$di->setShared('view', function() {
		static $view;
		if (!isset($view)) {
			$view = new Phalcon\Mvc\View\Simple();
			$view->setViewsDir(BASE_PATH . '/app/views/');
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

	echo (new Phalcon\Mvc\Application($di))
		->useImplicitView(false)
		->handle()
		->getContent();
}
catch (Throwable $t) {
	echo $t;
}
