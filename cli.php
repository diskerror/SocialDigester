#!/usr/bin/env php
<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

define('APP_PATH', realpath(__DIR__));

require APP_PATH . '/functions/errorHandler.php';
require APP_PATH . '/functions/cli.php';
require APP_PATH . '/vendor/autoload.php';

////////////////////////////////////////////////////////////////////////////////

try {

/**
 * Read the configs
 */
require APP_PATH . '/functions/config.php';

/**
 * Registering the application autoloader
 */
$loader = new \Phalcon\Loader();
$loader->registerDirs([
	APP_PATH . '/tasks/',
	APP_PATH . '/models/'
])->register();

////////////////////////////////////////////////////////////////////////////////

/**
 * Services are globally registered here
 */
$di = new Phalcon\Di\FactoryDefault\Cli();

$di->setShared('config', function () use ($config) {
    return $config;
});

$di->setShared('mongo', function() use ($config) {
	static $mongo;
	if( !isset($mongo) ) {
		$mongo = new MongoDB\Client($config->mongo);
	}
    return $mongo;
});

////////////////////////////////////////////////////////////////////////////////

/**
 * Create a console application
 */
$console = new Phalcon\Cli\Console($di);

/**
 * Process the console arguments
 */
$arguments = [];

if ( array_key_exists(1, $argv) ) {
	$arguments['task'] = $argv[1];

	if ( array_key_exists(2, $argv) ) {
		$arguments['action'] = $argv[2];

		if ( array_key_exists(3, $argv) ) {
			$arguments['params'][] = $argv[3];
		}
	}
}

/**
 * Run it.
 */
$console->handle($arguments);

}
catch ( Exception $e ) {
	echo $e;
}

if (array_key_exists('printNewLine', $config) && $config->printNewLine) {
	cout(PHP_EOL);
}
