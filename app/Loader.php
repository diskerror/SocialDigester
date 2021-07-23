<?php

class Loader
{
	protected static $_loader;

	static function register(array $dirs = []): void
	{
		if (!isset(self::$_loader)) {
			self::$_loader = new Phalcon\Loader();

			self::$_loader->registerDirs($dirs);

			self::$_loader->registerNamespaces([
				'Logic'     => __DIR__ . '/models/logic',
				'Resource'  => __DIR__ . '/models/resource',
				'Service'   => __DIR__ . '/models/service',
				'Structure' => __DIR__ . '/models/struct',
			]);

			self::$_loader->register();
		}
		elseif (count($dirs)) {
			self::$_loader->registerDirs($dirs, true);
			self::$_loader->register();
		}
	}
}
