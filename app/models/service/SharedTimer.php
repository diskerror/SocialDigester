<?php
/** @noinspection ALL */

namespace Service;

class SharedTimer
{
	/**
	 * @var Shmem
	 */
	protected Shmem $_shmem;

	/**
	 * @param string $id
	 * @param string $mode
	 * @param int    $permissions
	 * @param int    $size
	 */
	public function __construct(string $id, string $mode = 'a', int $permissions = 0666, int $size = 0)
	{
		$this->_shmem = new Shmem($id, $mode, $permissions, $size);
	}

	public function start(): void
	{
		$this->_shmem->write(microtime(true));
	}

	public function elapsed(): float
	{
		return (microtime(true) - (float) $this->_shmem->read());
	}

	public function delete()
	{
		$this->_shmem->delete();
	}
}
