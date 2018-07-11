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
