<?php

namespace Service\Application;


use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Url;
use Phalcon\Mvc\View\Engine\Php;
use Phalcon\Mvc\View\Simple;
use Service\View;

/**
 * Class Http
 *
 * @package Service\Application
 */
class Http extends DiAbstract
{
	/**
	 * Run application.
	 */
	public function run(array $argv): string
	{
		$di = new FactoryDefault();

		parent::_commonDi($di);

		$basePath = $this->_basePath;

//		$di->setShared('view', function() use ($basePath) {
//			static $view;
//			if (!isset($view)) {
//				$view = new View();
//				$view->setViewsDir($basePath . '/app/views/');
//				$view->setLayoutsDir($basePath . '/app/views/layouts/');
//				$view->setTemplateAfter('default');
//				$view->start();
//			}
//			return $view;
//		});

		$di->setShared('view', function() use ($basePath) {
			static $view;
			if (!isset($view)) {
				$view = new Simple();
				$view->setViewsDir($basePath . '/app/views/');
				$view->registerEngines(['.phtml' => Php::class]);
			}
			return $view;
		});

//		$di->setShared('url', function() {
//			static $url;
//			if (!isset($url)) {
//				$url = new Url();
//				$url->setBaseUri('/');
////                $url->setBasePath($this->_basePath);
//			}
//			return $url;
//		});

//		$di->setShared('dispatcher', function() use ($di) {
//			static $dispatcher;
//
//			if (!isset($dispatcher)) {
//				$events = $di->getShared('eventsManager');
//
//				$events->attach(
//					'dispatch:beforeException',
//					function($event, Dispatcher $dispatcher, \Exception $exception) {
//						switch ($exception->getCode()) {
//							case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
//								$dispatcher->forward([
//									'controller' => 'error',
//									'action'     => 'show404',
//								]);
//								return false;
//
//							default:
//								//  Check for controller not found exceptions.
//								if ($exception instanceof Dispatcher\Exception) {
//									$dispatcher->forward([
//										'controller' => 'error',
//										'action'     => 'show404',
//									]);
//									return false;
//								}
//
//								$dispatcher->forward([
//									'controller' => 'error',
//									'action'     => 'index',
//									'params'     => [$exception],
//								]);
//								return false;
//						}
//					}
//				);
//
//				$dispatcher = new Dispatcher();
//				$dispatcher->setEventsManager($events);
//			}
//
//			return $dispatcher;
//		});

		$application = new Application($di);
		$application->useImplicitView(false);

		$uri = $application->handle($argv['REQUEST_URI']);
		return $uri->getContent();
	}
}
