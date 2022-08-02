<?php

namespace Service\Application;

use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Manager;
use Service\Cache;
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
	 * @var Cache
	 */
	public Cache $cache;

	/**
	 * DiAbstract constructor.
	 *
	 * @param string $basePath
	 */
	final public function __construct(string $basePath)
	{
		$this->basePath = realpath($basePath);

		$this->cache = new Cache(require $this->basePath . '/app/config/cachePath.php', 'digester-');

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
				$configDirPath    = $self->basePath . '/app/config';
				$configFileName   = $configDirPath . '/config.php';
				$myConfigFileName = $configDirPath . '/my_config.php';
				$config           = $self->cache->config;

				if (
					$config === null ||
					filemtime($configFileName) > $self->cache->getModTime('config') ||
					(file_exists($myConfigFileName) && filemtime($configFileName) > $self->cache->getModTime('config'))
				) {
					$config = require $configFileName;

					$config->basePath = $self->basePath;

					if (file_exists($myConfigFileName)) {
						$config->replace(require $myConfigFileName);
					}

					$self->cache->config = $config;
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
