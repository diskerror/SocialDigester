<?php

class PidHandler
{
	protected $_pidFileName;

	protected static $_baseName;

	function __construct($procName)
	{
		$this->_pidFileName = self::getPath($procName);

		if( file_exists($this->_pidFileName) ) {
			throw new Exception('process "' . $procName . '" is already running or not stopped properly');
		}

		if ( !file_exists(self::getBase()) ) {
			throw new Exception(PHP_EOL . PHP_EOL . 'Create directory with proper permissions: sudo mkdir -pm 777 ' . self::getBase() . PHP_EOL . PHP_EOL);
		}

		file_put_contents($this->_pidFileName, getmypid());
	}

	function __destruct()
	{
		if ( file_exists($this->_pidFileName) ) {
			unlink($this->_pidFileName);
		}
	}

	public static function getBase()
	{
		if( !isset(self::$_baseName) ) {
			self::$_baseName = '/var/run/harvest';
		}
		return self::$_baseName;
	}

	public static function getPath($procName)
	{
		return self::getBase() . '/' . (string) $procName . '.pid';
	}

	public function exists()
	{
		return file_exists($this->_pidFileName);
	}

	public static function removeIfExists($procName)
	{
		$pidFile = self::getPath($procName);

		if( file_exists($pidFile) ) {
			$running = file_exists( '/proc/' . file_get_contents($pidFile) );
			unlink($pidFile);
			return $running;
		}

		return false;
	}

}
