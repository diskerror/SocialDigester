<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 2019-01-31
 * Time: 12:50
 */

namespace Structure\Config;


use Diskerror\Typed\TypedClass;

class Process extends TypedClass
{
	protected $name    = 'twitter_digester';
	protected $path    = '/var/run/twitter_digester';
	protected $procDir = '/proc/';
}
