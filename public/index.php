<?php

// ini_set('display_errors', '1');
// error_reporting(E_ALL);

define('APP_PATH', realpath(__DIR__ . '/..'));

require APP_PATH . '/functions/errorHandler.php';
require APP_PATH . '/vendor/autoload.php';

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
    $config->application->modelsDir
])->register();

////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Services are globally registered here
 */
$di = new Phalcon\Di\FactoryDefault();

/**
 * Sets the view component
 */
$di->setShared('view', function () use ($config) {
    $view = new Phalcon\Mvc\View\Simple();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
// $di->setShared('url', function () use ($config) {
//     $url = new Phalcon\Mvc\Url();
//     $url->setBaseUri($config->application->baseUri);
//     return $url;
// });

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
// $di->set('mongo', function() {
// 	static $mongo;
// 	if( !isset($mongo) ) {
// 		$mongo = new MongoDB\Client();
// 	}
//     return $mongo;
// }, true);

////////////////////////////////////////////////////////////////////////////////////////////////////
//	This is what makes up a controller for our simple application.
//		Map URLs to our models.
////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Starting the application
 * Assign service locator to the application
 */
$app = new Phalcon\Mvc\Micro($di);

/**
 * Not found handler
 */
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});

/**
 * Index.
 */
$app->get('/', function () use ($app) {
	$app->assets->addCss('css/jqcloud.min.css')
		->addCss('css/jquery.qtip.min.css');

	$app->assets
		->addJs('js/jquery.js')
		->addJs('js/jqcloud.min.js')
		->addJs('js/cloud1.js')
		->addJs('js/jquery.qtip.min.js')
		->addJs('js/imagesloaded.pkg.min.js');

	$app->view->setVar('terms', implode(', ', \Diskerror\Utilities\Registry::get('tracking_data')));

	echo $app->view->render('index');
});

/**
 * Tag Cloud.
 * Returns JSON of hashtags.
 */
$app->get('/tag-cloud', function () use ($app) {
	$app->view->setVar('obj', GetData::hashtags());
	echo $app->view->render('js');
});
$app->get('/hashtags', function () use ($app) {
	$app->view->setVar('obj', GetData::hashtags());
	echo $app->view->render('js');
});

////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Handle the request
 */
$app->handle();

}
catch ( Exception $e ) {
	echo $e;
}
