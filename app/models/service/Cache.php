<?php

namespace Service;

use Service\Exception\UnexpectedValueException;

class Cache
{
	public string $cachePath;
	public string $prefix;

	public function __construct(string $cachePath, string $prefix = '')
	{
		if (!file_exists($cachePath)) {
			throw new UnexpectedValueException("Cache path data file doesn't exist ($cachePath)");
		}

		if (substr($cachePath, -1) !== '/') {
			$cachePath .= '/';
		}

		$this->cachePath = $cachePath;
		$this->prefix    = $prefix;
	}

	public function __get(string $name)
	{
		$dataPath = $this->cachePath . $this->prefix . $name;
		if (!file_exists($dataPath)) {
			return null;
		}
		return unserialize(file_get_contents($dataPath));
	}

	public function __set(string $name, $value): void
	{
		file_put_contents($this->cachePath . $this->prefix . $name, serialize($value), LOCK_EX);
	}

	public function __isset($name): bool
	{
		return file_exists($this->cachePath . $this->prefix . $name);
	}

	public function __unset($name): void
	{
		$dataPath = $this->cachePath . $this->prefix . $name;
		if (file_exists($dataPath)) {
			unlink($dataPath);
		}
	}

	public function getModTime(string $name): int
	{
		$dataPath = $this->cachePath . $this->prefix . $name;
		if (file_exists($dataPath)) {
			return filemtime($dataPath);
		}
		return 0;
	}

}
