<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));

try {
	require BASE_PATH . '/vendor/diskerror/autoload/autoload.php';

	$app = new Service\Application\Http(BASE_PATH);
	echo $app->run($_SERVER);
}
catch (Throwable $t) {
	echo $t;
}
