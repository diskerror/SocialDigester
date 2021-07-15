<?php

namespace Logic;


use Structure\Config;

class ConfigFactory
{
	protected static $config;

	public static function get(): Config
	{
		if (!isset(self::$config)) {
			//	Always open this configuration file with it's default values.
			$configFile   = BASE_PATH . '/app/config/config.php';
			self::$config = new Config(require $configFile);

			//	Open all other files ending with '.php' as a configuration file.
			//	'glob' defaults to sorted.
			foreach (glob(BASE_PATH . '/app/config/*.php') as $cnf) {
				if ($cnf !== $configFile && !is_dir($cnf)) {
					self::$config->replace(require $cnf);
				}
			}
		}

		return self::$config;
	}
}
