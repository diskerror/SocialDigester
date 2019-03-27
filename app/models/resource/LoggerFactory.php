<?php

namespace Resource;

use Phalcon\Logger\Adapter\File;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\Formatter\Line;

/**
 * This logger class writes to both a named file and to STDERR.
 *
 * @copyright     Copyright (c) 2016 Reid Woodbury Jr.
 * @license       http://www.apache.org/licenses/LICENSE-2.0.html	Apache License, Version 2.0
 *
 * @method info($message)
 * @method warning($message)
 * @method critical($message)
 * @method emergency($message)
 * @method debug($message)
 */
class LoggerFactory
{
	/**
	 * Log message output format.
	 */
	const OUTPUT_FORMAT = "%type%\t%date%\t%message%";

	/**
	 * Log format object.
	 *
	 * @type Line
	 */
	protected static $_format;

	/**
	 * @type File
	 */
	protected $_file;

	/**
	 * @type Stream
	 */
	protected $_stream;

	/**
	 * LoggerFactory constructor.
	 *
	 * @param string $fileName
	 */
	function __construct(string $fileName)
	{
		$this->_file   = self::getFileLogger($fileName);
		$this->_stream = self::getStreamLogger();
	}

	/**
	 * @param $fileName
	 *
	 * @return File
	 */
	public static function getFileLogger($fileName): File
	{
		$file = new File($fileName);
		$file->setFormatter(self::getFormatter());
		return $file;
	}

	/**
	 * @return Line
	 */
	public static function getFormatter(): Line
	{
		if (!isset(self::$_format)) {
			self::$_format = new Line(self::OUTPUT_FORMAT);
		}
		return self::$_format;
	}

	/**
	 * @return Stream
	 */
	public static function getStreamLogger(): Stream
	{
		$stream = new Stream('php://stderr');
		$stream->setFormatter(self::getFormatter());
		return $stream;
	}

	/**
	 * Log the message.
	 * The function name becomes the log level
	 *
	 * @param string $key
	 */
	function __call($level, $params)
	{
		$message = $params[0];
		$this->_file->$level($message);

		switch ($level) {
			case 'info':
			case 'warning':
			case 'critical':
			case 'emergency':
			case 'debug':
				$this->_stream->$level($message);
		}
	}

}
