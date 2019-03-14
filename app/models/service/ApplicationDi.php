<?php

namespace Service;

use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Manager;
use Zend\Stdlib\ArrayUtils;

class ApplicationDi
{
	/**
	 * @var string
	 */
	protected static $_appPath;

	/**
	 * @var Phalcon\Di\FactoryDefault
	 */
	protected static $_di;

	public static function factory()
	{
		self::$_appPath = realpath(__DIR__ . '/../..');

		if (null === self::$_di) {
			self::$_di = new FactoryDefault();
		}

		self::$_di->setShared('eventsManager', function() {
			static $eventsManager;
			if (!isset($eventsManager)) {
				$eventsManager = new Manager();
			}
			return $eventsManager;
		});

		self::$_di->setShared('appConfig', function() {
			static $appConfig;

			if (!isset($appConfig)) {
				$appConfig = require self::$_appPath . '/config/application.config.php';

				if (file_exists(self::$_appPath . '/config/development.config.php')) {
					$appConfig = ArrayUtils::merge($appConfig, require self::$_appPath . '/config/development.config.php');
				}
			}

			return $appConfig;
		});
	}

	public static function getDi()
	{
		return self::$_di;
	}

}
