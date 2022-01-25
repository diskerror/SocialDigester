<?php

namespace Service;

use Service\Exception\RuntimeException;

class ShmemMaster extends Shmem
{
	/**
	 * @param string $id
	 * @param string $mode
	 * @param int $permissions
	 * @param int $size
	 */
	public function __construct(string $id, string $mode = 'c', int $permissions = 0666, int $size = 1024)
	{
		parent::__construct($id, $mode, $permissions, $size);
	}

	/**
	 *
	 */
	public function __destruct()
	{
		$this->delete();
		shmop_close($this->_shmop);
	}

}
