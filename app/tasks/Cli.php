<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 7/22/18
 * Time: 3:57 PM
 */

class Cli extends \Phalcon\Cli\Task
{
	protected static function print(string $s)
	{
		fwrite(STDOUT, $s);
	}

	protected static function println(string $s)
	{
		fwrite(STDOUT, $s . PHP_EOL);
	}
}
