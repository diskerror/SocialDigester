<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));

try {
	require 'vendor/autoload.php';
	require 'app/Loader.php';
	Loader::register([BASE_PATH . '/app/controllers/']);

	$app = new Service\Application\Http(BASE_PATH);
	$app->init();
	echo $app->run($_SERVER);
}
catch (Throwable $t) {
	echo $t;
}
