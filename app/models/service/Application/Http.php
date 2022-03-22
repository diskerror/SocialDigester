<?php
/** @noinspection SpellCheckingInspection */

namespace Service\Application;

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url;
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
	protected Application $_application;

	protected function _init(): void
	{
		$di = new FactoryDefault();

		$this->_commonDi($di);

		$self = $this;

//		$di->setShared('view', function() use ($self) {
//			static $view;
//			if (!isset($view)) {
//				$view = new View();
//				$view->setViewsDir($self->basePath . '/app/views/');
//				$view->setLayoutsDir($self->basePath . '/app/views/layouts/');
//				$view->setTemplateAfter('default');
//				$view->start();
//			}
//			return $view;
//		});

		$di->setShared('view', function() use ($self) {
			static $view;
			if (!isset($view)) {
				$view = new Simple();
				$view->setViewsDir($self->basePath . '/app/views/');
				$view->registerEngines(['.phtml' => Php::class]);
			}
			return $view;
		});

//		$di->setShared('url', function() {
//			static $url;
//			if (!isset($url)) {
//				$url = new Url();
//				$url->setBaseUri('/');
////                $url->setBasePath($this->basePath);
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

		$this->_application = new Application($di);
		$this->_application->useImplicitView(false);
	}

	/**
	 * Run application.
	 */
	public function run(array $argv): void
	{
		echo $this->_application->handle($argv['REQUEST_URI'])->getContent();
	}
}
