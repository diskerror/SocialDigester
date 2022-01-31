<?php

use MongoDB\BSON\UTCDateTime;
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
		StdIo::outf('%.2fms' . PHP_EOL, (float) (new Shmem('w'))() * 1000);
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
		(new Resource\MongoCollections\Tweets($this->config->mongo_db))->doIndex();
		(new Resource\MongoCollections\Tallies($this->config->mongo_db))->doIndex();
		(new Resource\MongoCollections\Snapshots($this->config->mongo_db))->doIndex();
		(new Resource\MongoCollections\Messages($this->config->mongo_db))->doIndex();
	}

	/**
	 * Clear tweets and tallies collections.
	 */
	public function clearTweetsAction()
	{
		(new Resource\MongoCollections\Tweets($this->config->mongo_db))->drop();
		(new Resource\MongoCollections\Tallies($this->config->mongo_db))->drop();
	}

	/**
	 * Returns 1 if PID exists, 0 if not.
	 */
	public function pidExistsAction()
	{
		echo (new Resource\PidHandler($this->config->process))->exists() ? 1 : 0;
	}

	public function messagesAction()
	{
		StdIo::jsonOut(
			(new Resource\MongoCollections\Messages($this->config->mongo_db))->find([
				'created' => ['$gt' => new UTCDateTime((time() - 3600) * 1000)],
			])
		);
	}

}
