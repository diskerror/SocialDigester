<?php

use Resource\PidHandler;
use Service\Shmem;
use Service\StdIo;

class AdminTask extends TaskMaster
{
	/**
	 * Displays the rate at which tweets are being consumed.
	 */
	public function rateAction()
	{
		StdIo::outf('%.2f tweets per second' . PHP_EOL, (new Shmem('r'))());
	}

	/**
	 * Displays the wait time between writes to MongoDB.
	 */
	public function waitAction()
	{
		StdIo::outf('%.2f' . PHP_EOL, (new Shmem('w'))());
	}

	/**
	 * Display the current aggregate configuration.
	 */
	public function showConfigAction()
	{
		StdIo::phpOut($this->config->toArray());
	}

	/**
	 * Reindex MongoDB collections.
	 */
	public function indexDbAction()
	{
		(new Resource\Tweets($this->config->mongo_db))->doIndex();
		(new Resource\Tallies($this->config->mongo_db))->doIndex();
		(new Resource\Snapshots($this->config->mongo_db))->doIndex();
		(new Resource\Messages($this->config->mongo_db))->doIndex();
	}

	/**
	 * Clear tweets and tallies collections.
	 */
	public function clearTweetsAction()
	{
		(new Resource\Tweets($this->config->mongo_db))->drop();
		(new Resource\Tallies($this->config->mongo_db))->drop();
	}

	/**
	 * Returns 1 if PID exists, 0 if not.
	 */
	public function pidExistsAction()
	{
		echo (new PidHandler($this->config->process))->exists() ? 1 : 0;
	}

}
