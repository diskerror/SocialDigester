<?php

namespace Logic;

use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\Formatter\Line;
use function fwrite;
use const PHP_EOL;
use const STDERR;

/**
 * This logger class writes to both a named file and to STDERR.
 *
 * @copyright     Copyright (c) 2016 Reid Woodbury Jr.
 * @license       http://www.apache.org/licenses/LICENSE-2.0.html	Apache License, Version 2.0
 */
class LoggerFactory
{
	/**
	 * Log message output format.
	 */
	const OUTPUT_FORMAT = "%type%\t%date%\t%message%";

	/**
	 * @type Logger
	 */
	protected $_logger;

	/**
	 */
	function __construct($fileName)
	{
		$adapter = new Stream($fileName);
		$adapter->setFormatter(new Line(self::OUTPUT_FORMAT));
		$this->_logger = new Logger('messages', ['main' => $adapter]);
	}

	/**
	 * Log the message.
	 * The function name becomes the log level
	 *
	 * @param string $level
	 * @param array  $params
	 */
	function __call($level, $params)
	{
		switch ($level) {
			case 'critical':
			case 'emergency':
			case 'error':
//			case 'debug':
				fwrite(STDERR, $params[0] . PHP_EOL);
		}
		$this->_logger->$level($params[0]);
	}

}
