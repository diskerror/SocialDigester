<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/28/18
 * Time: 12:49 PM
 */

class Tweets extends \Phalcon\Mvc\MongoCollection
{
	public function initialize()
	{
		$this->setSource('tweets');
	}
}

/*
 *
 * https://forum.phalconphp.com/discussion/14885/mongodb-support-in-phalcon-3-php-7
 *
 */
