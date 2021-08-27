<?php

namespace Diskerror;

use Ds\Map;
use Ds\Vector;
use Exception;

if (!extension_loaded('ds') || PHP_VERSION_ID < 70300) {
	require 'vendor/autoload.php';
	return;
}

if (defined('HHVM_VERSION') || (function_exists('zend_loader_file_encoded') && zend_loader_file_encoded())) {
	throw new Exception('Cannot use this autoloader.');
}

final class Autoloader
{
	private static $classmap;
	private static $namespaces;
	private static $psr4;

	public static function init()
	{
		self::$classmap   = new Map(require 'vendor/composer/autoload_classmap.php');
		self::$namespaces = new Map(require 'vendor/composer/autoload_namespaces.php');
		self::$psr4       = new Map(require 'vendor/composer/autoload_psr4.php');
	}

	public static function loader($class)
	{
		if (self::$classmap->hasKey($class)) {
			require self::$classmap->offsetGet($class);
			return true;
		}

		if (self::$namespaces->hasKey($class)) {
			require self::$namespaces->offsetGet($class);
			return true;
		}

		$classV         = new Vector(explode('\\', $class));
		$requestedClass = '';
		for ($p = 0; $p < $classV->count(); ++$p) {
			$requestedClass .= $classV->get($p) . '\\';
			if (self::$psr4->hasKey($requestedClass)) {
				$workingClassPaths = self::$psr4->get($requestedClass);
				for ($wc = 0; $wc < count($workingClassPaths); ++$wc) {
					$workingClassFile =
						self::$psr4->get($requestedClass)[$wc] . '/' .
						implode('/', $classV->slice($p + 1)->toArray()) . '.php';

					if (file_exists($workingClassFile)) {
						require $workingClassFile;
						return true;
					}
				}
			}
		}

		return false;
	}

	public static function loadFiles()
	{
		foreach (require 'vendor/composer/autoload_files.php' as $file) {
			require_once $file;
		}
	}
}

Autoloader::init();
spl_autoload_register('Diskerror\Autoloader::loader', false, true);
Autoloader::loadFiles();
