<?php

namespace Service;

use Service\Exception\RuntimeException;
use function is_readable;
use const PHP_EOL;

class StaticTimer
{
	private const TIME_FILE_PREFIX = '/dev/shm/politicator/timer.';

	public static function start(string $timerName = ''): void
	{
		file_put_contents(self::TIME_FILE_PREFIX . $timerName, microtime(true) . PHP_EOL);
	}

	public static function elapsed(string $timerName = ''): float
	{
		$fname = self::TIME_FILE_PREFIX . $timerName;

		if (!file_exists($fname) || !is_readable($fname)) {
			throw new RuntimeException('bad timer file: ' . $fname);
		}

		return (microtime(true) - (float) file_get_contents($fname));
	}
}
