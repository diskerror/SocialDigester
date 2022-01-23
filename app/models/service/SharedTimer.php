<?php

namespace Service;

class SharedTimer
{
	private $_id = '';

	public function __construct(string $id = '')
	{
		$this->_id = $id;
	}

	public function start(): void
	{
		(new Shmem($this->_id))->write(microtime(true));
	}

	public function elapsed(): float
	{
		return (microtime(true) - (float) (new Shmem($this->_id))->read());
	}

	public function delete()
	{
		(new Shmem($this->_id))->delete();
	}
}
