<?php

namespace Service;

use Service\Exception\RuntimeException;
use function count;
use function ftok;
use function readdir;

class Shmem
{
	private $_shmop;

	/**
	 * @param string $id
	 * @param string $mode
	 * @param int $permissions
	 * @param int $size
	 */
	public function __construct(string $id, string $mode = 'c', int $permissions = 0666, int $size = 1024)
	{
		$this->_shmop = shmop_open(ftok(__FILE__, $id), $mode, $permissions, $size);

		if ($this->_shmop === false) {
			throw new RuntimeException('could not open or create shared memory');
		}
	}

	/**
	 *
	 */
	public function __destruct()
	{
		shmop_close($this->_shmop);
	}

	/**
	 * @param int $offset
	 * @param int $size
	 * @return string
	 */
	public function __invoke(...$args): string
	{
		switch (count($args)) {
			case 0:
				$args = [0, 0];
				break;

			case 1:
				$args[1] = 0;
				break;

			case 2;
				break;

			default:
				throw new RuntimeException('too many arguments');
		}

		return $this->read($args[0], $args[1]);
	}

	/**
	 * @param int $offset
	 * @param int $size
	 * @return string
	 */
	public function read(int $offset = 0, int $size = 0): string
	{
		if ($size <= 0) {
			$size = $this->size();
		}

		return shmop_read($this->_shmop, $offset, $size);
	}

	public function size(): int
	{
		return shmop_size($this->_shmop);
	}

	public function delete(): void
	{
		if (shmop_delete($this->_shmop) === false) {
			throw new RuntimeException('problem deleting shared memory object');
		}
	}

	/**
	 * @param string $data
	 * @param int $size
	 * @return string
	 */
	public function write(string $data, int $offset = 0): void
	{
		shmop_write($this->_shmop, $data, $offset);
	}

}
