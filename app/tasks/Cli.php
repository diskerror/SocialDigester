<?php

use Phalcon\Cli\Task;


class Cli extends Task
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
