<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

try {
	//	Models are loaded with the Composer autoloader.
	require __DIR__ . '/../vendor/autoload.php';

	(new Phalcon\Loader())
		->registerDirs([__DIR__ . '/../app/controllers/'])
		->register();

	(new Service\Application\Http(__DIR__ . '/..'))
		->init()
		->run();
}
catch (Throwable $t) {
	echo $t;
}
