<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));

try {
	require '../diskerror_autoloader.php';

	$app = new Service\Application\Http(BASE_PATH);
	echo $app->run($_SERVER);
}
catch (Throwable $t) {
	echo $t;
}
