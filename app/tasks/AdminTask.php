<?php

use Logic\ConsumeTweets;
use MongoDB\BSON\UTCDateTime;
use Resource\MongoCollection;
use Service\Shmem;
use Service\StdIo;

class AdminTask extends TaskMaster
{
	/**
	 * Displays the rate at which tweets are being consumed.
	 */
	public function rateAction()
	{
		StdIo::outf('%.2f (%.2f) tweets per second' . PHP_EOL, (new Shmem('r'))(), (new Shmem('i'))());
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
		(new MongoCollection($this->config, 'tweets'))->doIndex();
		(new MongoCollection($this->config, 'tallies'))->doIndex();
		(new MongoCollection($this->config, 'snapshots'))->doIndex();
		(new MongoCollection($this->config, 'messages'))->doIndex();
	}

	/**
	 * Clear tweets and tallies collections.
	 */
	public function clearTweetsAction()
	{
		(new MongoCollection($this->config, 'tweets'))->drop();
		(new MongoCollection($this->config, 'tallies'))->drop();
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
			(new MongoCollection($this->config, 'messages'))->find([
				'created' => ['$gt' => new UTCDateTime((time() - 3600) * 1000)],
			])
		);
	}

}
