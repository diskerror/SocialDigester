<?php

class DiBase
{
	private function __construct() { }

	public static function init(\Phalcon\Di\FactoryDefault $di, \Phalcon\Config $config)
	{
		$this->setShared('config', function() use ($config) {
			return $config;
		});

// 		$this->setShared('mongo', function() use ($config) {
// 			static $mongo;
// 			if (!isset($mongo)) {
// 				$mongo = new MongoDB\Client($config->mongo->host);
// 			}
// 			return $mongo;
// 		});

//		$this->setShared('tweets', function() use ($config) {
//			static $collection;
//			if (!isset($collection)) {
//				tweets = $config->tweets;
//				$collection =
//					(new MongoDB\Client(tweets->host))
//						->{tweets->database}
//						->{tweets->collection};
//			}
//			return $collection;
//		});

		$this->setShared('db', function() use ($config) {
			static $db;
			if (!isset($db)) {
				$db = (new MongoDB\Client($config->mongo_db->host))
					->{$config->mongo_db->database};
			}
			return $db;
		});

		$this->setShared('view', function() {
			static $view;
			if (!isset($view)) {
				$view = new Phalcon\Mvc\View\Simple();
				$view->setViewsDir(APP_PATH . '/views/');
			}
			return $view;
		});

// 		$this->setShared('url', function () use ($config) {
// 			$url = new Phalcon\Mvc\Url();
// 			$url->setBaseUri($config->application->baseUri);
// 			return $url;
// 		});

		$di = $this;
		$this->setShared(
			'dispatcher',
			function() use ($di) {
				$events = $di->getShared('eventsManager');

				$events->attach(
					"dispatch:beforeException",
					function($event, $dispatcher, $exception) {
						switch ($exception->getCode()) {
							case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
							case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
								$dispatcher->forward([
									'controller' => 'error',
									'action'     => 'show404',
								]);
								return false;
						}
					}
				);

				$dispatcher = new PhDispatcher();
				$dispatcher->setEventsManager($events);
				return $dispatcher;
			}
		);
	}

}
