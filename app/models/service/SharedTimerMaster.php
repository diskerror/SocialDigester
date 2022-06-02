<?php

namespace Service;

class SharedTimerMaster extends SharedTimer
{
	/**
	 * @param string $id
	 * @param string $mode
	 * @param int    $permissions
	 * @param int    $size
	 */
	public function __construct(string $id, string $mode = 'c', int $permissions = 0666, int $size = 1024)
	{
		$this->_shmem = new ShmemMaster($id, $mode, $permissions, $size);
	}
}
