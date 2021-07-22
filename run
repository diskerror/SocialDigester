#!/usr/bin/env php
<?php

//ini_set('display_errors', '0');
//error_reporting(E_ERROR);

define('BASE_PATH', __DIR__);

try {
	require 'vendor/autoload.php';
	require 'app/Loader.php';
	Loader::register([__DIR__ . '/app/tasks/']);

	$cli = new Service\Application\Cli(__DIR__);
	$cli->init();
	$cli->run($argv);
}
catch (Phalcon\Cli\Dispatcher\Exception $e) {
	fwrite(STDERR, $e->getMessage() . PHP_EOL);
}
catch (Throwable $t) {
	fwrite(STDERR, $t . PHP_EOL);
}
