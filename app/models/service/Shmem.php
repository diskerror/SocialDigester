<?php
/** @noinspection ALL */

namespace Service;

use Service\Exception\BadMethodCallException;
use Service\Exception\RuntimeException;
use Service\Exception\ShmemOpenException;
use Service\Exception\UnexpectedValueException;

/**
 * class Shmem
 *
 * This class will NOT remove the shared memory value when going out of scope.
 */
class Shmem
{
	protected $_shmop;

	/**
	 * @param string $id
	 * @param string $mode
	 * @param int    $permissions
	 * @param int    $size
	 */
	public function __construct(string $id, string $mode = 'a', int $permissions = 0666, int $size = 0)
	{
		$this->_shmop = @shmop_open(ftok(__FILE__, $id), $mode, $permissions, $size);

		if ($this->_shmop === false) {
			throw new ShmemOpenException('could not open or create shared memory');
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
	 * @return string
	 */
	public function __invoke(...$args): string
	{
		if (count($args) > 2) {
			throw new BadMethodCallException('too many arguments');
		}

		return $this->read(...$args);
	}

	/**
	 * @param int $offset
	 * @param int $size
	 *
	 * @return string
	 */
	public function read(int $offset = 0, int $size = 0): string
	{
		if ($size < 0) {
			throw new UnexpectedValueException('offset cannot be less than zero');
		}
		elseif ($size === 0) {
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
	 * @param int    $offset
	 *
	 * @return void
	 */
	public function write(string $data, int $offset = 0): void
	{
		shmop_write($this->_shmop, $data, $offset);
	}

}
