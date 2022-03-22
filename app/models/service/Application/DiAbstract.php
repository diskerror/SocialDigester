<?php

namespace Service\Application;

use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Manager;
use Structure\Config;

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
	public string $basePath;

	/**
	 * DiAbstract constructor.
	 */
	final public function __construct()
	{
		$this->basePath = realpath(__DIR__ . '/../../../..');

		$this->_init();
	}

	abstract protected function _init(): void;

	/**
	 * Common dependencies for both CLI and HTTP.
	 *
	 * @param FactoryDefault $di
	 */
	final protected function _commonDi(FactoryDefault $di): void
	{
		$self = $this;

		$di->setShared('config', function() use ($self): Config {
			static $config;

			if (!isset($config)) {
				$config = require $self->basePath . '/app/config/config.php';

				$config->basePath = $self->basePath;

				$myConfigFile = $config->configPath . '/my_config.php';
				if (file_exists($myConfigFile)) {
					$config->replace(require $myConfigFile);
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
	 * Run application.
	 */
	abstract public function run(array $argv): void;
}
