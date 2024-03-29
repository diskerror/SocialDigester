<?php

namespace Resource;

use Exception;
use Structure\Config\Process;

class PidHandler
{
	protected string $_fullProcessFileName;

	protected string $_basePath;

	/**
	 * @param Process $config
	 */
	public function __construct(Process $config)
	{
		$this->_basePath = $config->path;
		if (substr($this->_basePath, -1) !== '/') {
			$this->_basePath .= '/';
		}

		$this->_fullProcessFileName = $this->_basePath . $config->name . '.pid';
	}

	public function __destruct()
	{
		$this->removeIfExists();
	}

	/**
	 * @return boolean
	 */
	public function setFile(): bool
	{
		if (file_exists($this->_fullProcessFileName)) {
			return false;
		}

		if (!file_exists($this->_basePath)) {
			throw new Exception(PHP_EOL . PHP_EOL . 'Create directory with proper permissions: sudo mkdir -pm 777 ' . $this->_basePath . PHP_EOL . PHP_EOL);
		}

		file_put_contents($this->_fullProcessFileName, getmypid());
		return true;
	}

	/**
	 * @return boolean
	 */
	public function exists(): bool
	{
		return file_exists($this->_fullProcessFileName);
	}

	/**
	 * @return boolean
	 */
	public function removeIfExists(): bool
	{
		if ($this->exists()) {
			unlink($this->_fullProcessFileName);
			return true;
		}

		return false;
	}

}
