<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 2019-03-21
 * Time: 13:29
 */

use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url;
use Resource\LoggerFactory;
use Resource\MongoCollectionManager;
use Service\View;
use Structure\Config;

/**
 * Trait PropertiesTrait
 *
 * @property $config
 * @property $mongodb
 * @property $eventsManager
 * @property $logger
 * @property $view
 * @property $url
 * @property $dispatcher
 */
trait PropertiesTrait
{
	/** @var Config $config */
	/** @var MongoCollectionManager $mongodb */
	/** @var Manager $eventsManager */
	/** @var LoggerFactory $logger */
	/** @var View $view */
	/** @var Url $url */
	/** @var Dispatcher $dispatcher */
}
