<?php

namespace Service;

use Service\Exception\UnexpectedValueException;

class Cache
{
	private string $_cachePath;
	private string $_prefix;

	public function __construct(string $cachePath, string $prefix = '')
	{
		if (!file_exists($cachePath)) {
			throw new UnexpectedValueException("Cache path data file doesn't exist ($cachePath)");
		}

		if (substr($cachePath, -1) !== '/') {
			$cachePath .= '/';
		}

		$this->_cachePath = $cachePath;
		$this->_prefix    = $prefix;
	}

	public function __get(string $name)
	{
		$dataPath = $this->_cachePath . $this->_prefix . $name;
		if (!file_exists($dataPath)) {
			return null;
		}
		return unserialize(file_get_contents($dataPath));
	}

	public function __set(string $name, $value): void
	{
		file_put_contents($this->_cachePath . $this->_prefix . $name, serialize($value), LOCK_EX);
	}

	public function __isset($name): bool
	{
		return file_exists($this->_cachePath . $this->_prefix . $name);
	}

	public function __unset($name): void
	{
		$dataPath = $this->_cachePath . $this->_prefix . $name;
		if (file_exists($dataPath)) {
			unlink($dataPath);
		}
	}

	public function getModTime(string $name): int
	{
		$dataPath = $this->_cachePath . $this->_prefix . $name;
		if (file_exists($dataPath)) {
			return filemtime($dataPath);
		}
		return 0;
	}

	public function setPrefix(string $prefix)
	{
		$this->_prefix = $prefix;
	}

}
