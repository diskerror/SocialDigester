<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

define('APP_PATH', realpath(__DIR__ . '/..'));

////////////////////////////////////////////////////////////////////////////////

try {

	require APP_PATH . '/functions/errorHandler.php';
	require APP_PATH . '/vendor/autoload.php';

	/**
	 * Read the configs
	 */
	require APP_PATH . '/functions/config.php';

	/**
	 * Registering the application autoloader
	 */
	$loader = new \Phalcon\Loader();
	$loader->registerDirs([
		APP_PATH . '/models/',
		APP_PATH . '/structs/',
	])->register();

////////////////////////////////////////////////////////////////////////////////

	/**
	 * Services are globally registered here
	 */
	$di = new Phalcon\Di\FactoryDefault();

	$di->setShared('config', function() use ($config) {
		return $config;
	});

	/**
	 * Sets the view component
	 */
	$di->setShared('view', function() use ($config) {
		static $view;
		if (!isset($view)) {
			$view = new Phalcon\Mvc\View\Simple();
			$view->setViewsDir(APP_PATH . '/views/');
		}
		return $view;
	});

	/**
	 * The URL component is used to generate all kind of urls in the application
	 */
// $di->setShared('url', function () use ($config) {
//	   $url = new Phalcon\Mvc\Url();
//	   $url->setBaseUri($config->application->baseUri);
//	   return $url;
// });

	/**
	 * Database connection is created based in the parameters defined in the configuration file
	 */
	$di->setShared('mongo', function() use ($config) {
		static $mongo;
		if (!isset($mongo)) {
			$mongo = new MongoDB\Client($config->mongo);
		}
		return $mongo;
	});

////////////////////////////////////////////////////////////////////////////////
//	This is what makes up a controller for our simple application.
//		Map URLs to our models.
////////////////////////////////////////////////////////////////////////////////

	/**
	 * Starting the application
	 * Assign service locator to the application
	 */
	$app = new Phalcon\Mvc\Micro($di);

	/**
	 * Not found handler
	 */
	$app->notFound(function() use ($app) {
		$app->response->setStatusCode(404, "Not Found")->sendHeaders();
		echo $app['view']->render('404');
	});

	/**
	 * Index.
	 */
	$app->get('/', function() use ($app) {
		$indexCache = $app->config->index_cache;
		$indexCache->back->frontend = Phalcon\Cache\Frontend\Factory::load($indexCache->front);
		$cache = Phalcon\Cache\Backend\Factory::load($indexCache->back);

		$output = $cache->get('');

		if ($output === null) {
			$app->assets
				->addCss('css/jqcloud.min.css')
				->addCss('css/jquery.qtip.min.css')
				->addCss('css/jquery-ui.min.css')
				->addCss('css/jquery-ui.structure.min.css');

			$app->assets
				->addJs('js/jquery-3.3.1.min.js')
				->addJs('js/jqcloud.min.js')
				->addJs('js/cloud1.js')
				->addJs('js/jquery.qtip.min.js')
				->addJs('js/imagesloaded.pkg.min.js')
				->addJs('js/jquery-ui.min.js');

			$app->view->setVar('terms', implode(', ', (array)$app->config->twitter->track));

			$output = $app->view->render('index');
			$cache->save('', $output);
		}

		echo $output;
	});

	/**
	 * Tag Cloud.
	 * Returns JSON of hashtags.
	 */
	$app->get('/tag-cloud', function() use ($app) {
		$tagCloudCache = $app->config->tag_cloud_cache;
		$tagCloudCache->back->frontend = Phalcon\Cache\Frontend\Factory::load($tagCloudCache->front);
		$cache = Phalcon\Cache\Backend\Factory::load($tagCloudCache->back);

		$output = $cache->get('');

		if ($output === null) {
			$tally = new Tally\TagCloud\Hashtags($app->mongo);
			$app->view->setVar('obj', $tally->get($app->config->word_stats));
			$output = $app->view->render('js');
			$cache->save('', $output);
		}

		echo $output;
	});
	$app->get('/hashtags', function() use ($app) {
		$tally = new Tally\TagCloud\Hashtags($app->mongo);
		$app->view->setVar('obj', $tally->get($app->config->word_stats));
		echo $app->view->render('js');
	});
	$app->get('/text', function() use ($app) {
		$tally = new Tally\TagCloud\Text($app->mongo);
		$app->view->setVar('obj', $tally->get($app->config->word_stats));
		echo $app->view->render('js');
	});

////////////////////////////////////////////////////////////////////////////////

	/**
	 * Handle the request
	 */
	$app->handle();

}
catch (Throwable $t) {
	echo $t;
}
