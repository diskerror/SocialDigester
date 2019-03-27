<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 2019-01-31
 * Time: 12:42
 */

namespace Structure\Config;


use Diskerror\Typed\TypedClass;

class Twitter extends TypedClass
{
	protected $url  = 'https://stream.twitter.com/1.1/';
	protected $auth = [OAuth::class];
}
