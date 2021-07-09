<?php

namespace Service\Application;

use OutOfRangeException;
use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Manager;
use Phalcon\Loader;
use Resource\LoggerFactory;
use Resource\MongoCollectionManager;
use Structure\Config;
use Zend\Stdlib\ArrayUtils;

/**
 * Class DiAbstract
 *
 * @package Service\Application
 */
abstract class DiAbstract
{
	/**
	 * @var string
	 */
	protected $_basePath;

	/**
	 * @var (application)
	 */
	protected $_application;

	protected $_loader;

	/**
	 * DiAbstract constructor.
	 *
	 * @param string $basePath
	 */
	final public function __construct(string $basePath)
	{
		if (!is_dir($basePath)) {
			throw new OutOfRangeException('"$_basePath" should be the directory containing this project.');
		}

		$this->_basePath = $basePath;
//		$this->_loader= new Loader();
	}

	/**
	 * Common dependencies for both CLI and HTTP.
	 *
	 * @param FactoryDefault $di
	 */
	final protected function _commonDi(FactoryDefault $di): void
	{
		$self = $this;

//		$di->setShared('config', function() use ($self) {
//			static $config;
//
//			if (!isset($config)) {
//				$configName = $self->_basePath . '/app/config/application.config.php';
//				$devName    = $self->_basePath . '/app/config/development.config.php';
//
//				//	File must exist.
//				$config = require $configName;
//
//				if (file_exists($devName)) {
//					$config = ArrayUtils::merge($config, require $devName);
//				}
//
//				$config = new Config($config);
//
//				//	Open all other files in this directory that end with '.php' as a configuration file.
//				//	'glob' defaults to sorted.
//				foreach (glob($self->_basePath . '/app/config/*.php') as $g) {
//					if ($g !== $configName && $g !== $devName && !is_dir($g)) {
//						$config->replace(require $g);
//					}
//				}
//			}
//
//			return $config;
//		});
//
//		$di->setShared('mongodb', function() use ($di) {
//			static $mongodb;
//			if (!isset($mongodb)) {
//				$mongodb = new MongoCollectionManager($di->getShared('config')->mongodb);
//			}
//			return $mongodb;
//		});

		$di->setShared('eventsManager', function() {
			static $eventsManager;
			if (!isset($eventsManager)) {
				$eventsManager = new Manager();
			}
			return $eventsManager;
		});

//		$di->setShared('logger', function() use ($self) {
//			static $logger;
//			if (!isset($logger)) {
////				$logger = LoggerFactory::getFileLogger($self->_basePath . '/' . $config->process->name . '.log');
//				$logger = LoggerFactory::getStreamLogger();
//			}
//			return $logger;
//		});
	}

	/**
	 * @param $di
	 */
	abstract public function init();

	/**
	 * Run application.
	 */
	abstract public function run(array $args): string;
}
