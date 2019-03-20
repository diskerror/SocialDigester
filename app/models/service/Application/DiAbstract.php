<?php

namespace Service\Application;

use OutOfRangeException;
use Phalcon\Events\Manager;
use Structure\Config;
use Zend\Stdlib\ArrayUtils;
use function array_map;
use function gettype;
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

	private $_nameReplacement = '';

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

	final protected function _commonDi(&$di): void
	{
		$self = $this;

		$di->setShared('config', function() use ($self) {
			static $config;

			if (!isset($config)) {
				$configName = $self->_basePath . '/app/config/application.config.php';
				$devName    = $self->_basePath . '/app/config/development.config.php';

				//	File must exist.
				$config = require $configName;

				if (file_exists($devName)) {
					$config = ArrayUtils::merge($config, require $devName);
				}

				//	Open all other files in this directory and ending with '.php' as a configuration file.
				//	'glob' defaults to sorted.
				foreach (glob($self->_basePath . '/app/config/*.php') as $g) {
					if ($g !== $configName && $g !== $devName && !is_dir($g)) {
						$config = ArrayUtils::merge($config, require $g);
					}
				}

				$config = new Config($config);
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
