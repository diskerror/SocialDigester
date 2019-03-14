<?php

namespace Service;


use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url;
use Service\View;

class ApplicationDiHttp extends ApplicationDi
{
    public static function factory()
    {
		self::$_di = new FactoryDefault();

		parent::factory();

		self::$_di->setShared('view', function () {
            static $view;
            if (!isset($view)) {
                $view = new View();
                $view->setViewsDir(self::$_appPath . '/views/');
                $view->setLayoutsDir(self::$_appPath . '/views/layouts/');
                $view->setTemplateAfter('default');
                $view->start();
            }
            return $view;
        });

        self::$_di->setShared('url', function () {
            static $url;
            if (!isset($url)) {
                $url = new Url();
                $url->setBaseUri('/');
//                $url->setBasePath(self::$_appPath);
            }
            return $url;
        });

        self::$_di->setShared('dispatcher', function () {
            static $dispatcher;

            if (!isset($dispatcher)) {
                $events = $this->getShared('eventsManager');

                $events->attach(
                    'dispatch:beforeException',
                    function ($event, $dispatcher, $exception) {
                        switch ($exception->getCode()) {
                            case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                                $dispatcher->forward([
                                    'controller' => 'error',
                                    'action'     => 'show404',
                                ]);
                                return false;

                            default:
                                //  Check for controller not found exceptions.
                                if($exception instanceof Dispatcher\Exception){
                                    $dispatcher->forward([
                                        'controller' => 'error',
                                        'action'     => 'show404',
                                    ]);
                                    return false;
                                }

                                $dispatcher->forward([
                                    'controller' => 'error',
                                    'action'     => 'index',
                                    'params'     => [$exception],
                                ]);
                                return false;
                        }
                    }
                );

                $dispatcher = new Dispatcher();
                $dispatcher->setEventsManager($events);
            }

            return $dispatcher;
        });
    }
}
