<?php

use Logic\ConsumeTweets;
use MongoDB\BSON\UTCDateTime;
use Resource\CollectionFactory;
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
		CollectionFactory::tweets($this->config)->doIndex();
		CollectionFactory::tallies($this->config)->doIndex();
		CollectionFactory::snapshots($this->config)->doIndex();
		CollectionFactory::messages($this->config)->doIndex();
	}

	/**
	 * Clear tweets and tallies collections.
	 */
	public function clearTweetsAction()
	{
		CollectionFactory::tweets($this->config)->drop();
		CollectionFactory::tallies($this->config)->drop();
	}

	/**
	 * Returns 1 if PID exists, 0 if not.
	 */
	public function pidExistsAction()
	{
		StdIo::outln((new Resource\PidHandler($this->config->process))->exists() ? 1 : 0);
	}


	/**
	 * Returns 1 if consume process is running, zero if not.
	 */
	public function detectRunningAction()
	{
		StdIo::outln(ConsumeTweets::detectRunning());
	}


	public function messagesAction()
	{
		StdIo::jsonOut(
			CollectionFactory::messages($this->config)->find([
				'created' => ['$gt' => new UTCDateTime((time() - 3600) * 1000)],
			])
		);
	}

}
