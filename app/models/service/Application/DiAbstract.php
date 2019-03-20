<?php

namespace Service\Application;

use OutOfRangeException;
use Phalcon\Events\Manager;
use Zend\Stdlib\ArrayUtils;
use function is_dir;

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
	}

	final protected function _commonDi(&$di)
	{
		$self = $this;

		$di->setShared('config', function() use ($self) {
			static $config;

			if (!isset($config)) {
				$config = require $self->_basePath . '/app/config/application.config.php';

				if (file_exists($self->_basePath . '/app/config/development.config.php')) {
					$config =
						ArrayUtils::merge($config, require $self->_basePath . '/app/config/development.config.php');
				}
			}

			return $config;
		});

		$di->setShared('eventsManager', function() {
			static $eventsManager;
			if (!isset($eventsManager)) {
				$eventsManager = new Manager();
			}
			return $eventsManager;
		});
	}

	/**
	 * @param $di
	 */
	abstract public function initDi();

	/**
	 * Run application.
	 */
	abstract public function run(): void;
}
