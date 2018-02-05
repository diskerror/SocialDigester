<?php

use \Phalcon\Mvc\Dispatcher as PhDispatcher;

class Di extends Phalcon\Di\FactoryDefault
{
	function __construct(\Phalcon\Config $config)
	{
		parent::__construct();

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

		$this->setShared('tweets', function() use ($config) {
			static $collection;
			if (!isset($collection)) {
				$mongo = $config->mongo;
				$collection =
					(new MongoDB\Client($mongo->host))
						->{$mongo->database}
						->{$mongo->collection};
			}
			return $collection;
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
