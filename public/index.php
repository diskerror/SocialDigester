<?php

//ini_set('display_errors', '1');
//error_reporting(E_ALL);

if (array_key_exists('_url', $_GET) && $_GET['_url'] == 404) {
	echo 'error 404';
	exit;
}

try {
	require __DIR__ . '/../vendor/diskerror/autoload/autoload.php';

	$app = new Service\Application\Http(__DIR__ . '/..');
	$app->run($_SERVER);
}
catch (Throwable $t) {
	echo $t;
}
