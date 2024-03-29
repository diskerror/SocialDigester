<?php
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */

/** @noinspection ALL */

use Logic\ConsumeTweets;
use MongoDB\BSON\UTCDateTime;
use Resource\LoggerFactory;
use Service\SharedTimer;
use Service\Shmem;
use Service\StdIo;

class TweetsTask extends TaskMaster
{
	/**
	 * Start process of receiving tweets.
	 */
	public function getAction(): void
	{
		Logic\ConsumeTweets::exec($this->config);
	}

	/**
	 * Start consuming tweets in the background.
	 *
	 * @return void
	 */
	public function startBgAction(): void
	{
		switch (pcntl_fork()) {
			case -1:
				throw new RuntimeException('could not fork (-1)');

			case 0:
				sleep(1);
				Logic\ConsumeTweets::exec($this->config);
				break;

			default:
		}
	}

	/**
	 *
	 * @return void
	 */
	public function checkRunningAction(): void
	{
		try {
			$rate = (new Shmem('r'))();
		}
		catch (Throwable $t) {
			$rate = 0;
		}

		if (!ConsumeTweets::detectRunning() || $rate > 80) {
			$this->stopAction();
			$logger = new LoggerFactory($this->config->basePath . '/consume.log');
			sleep(4);
			$this->startBgAction();
		}
	}

	/**
	 * Stop tweet consumption.
	 */
	public function stopAction()
	{
		$pidHandler = new Resource\PidHandler($this->config->process);
		if ($pidHandler->removeIfExists()) {
			StdIo::outln('Running process was halted.');
		}
		else {
			StdIo::outln('Process was not running.');
		}
	}

	/**
	 * Place for test code.
	 */
	public function testAction()
	{
		StdIo::phpOut($this->config);

//		StdIo::jsonOut((new Resource\MongoCollections\Tallies($this->config->mongo_db))->find(
//			[
//				'created' => ['$gte' => new UTCDateTime((time() - 600) * 1000)],
//			],
//			[
//				'projection' => ['users' => 1,],
//			]
//		));

//		$timer = new SharedTimer('x');
//		$timer->start();
//		for ($i = 0; $i < 10000; ++$i) {
//			$tmp = time() - 60;
////			$tmp = strtotime('60 seconds ago');
//			unset($tmp);
//		}
//		StdIo::outln($timer->elapsed());

//		$tallies = (new Tallies($this->config->mongo_db))->find([
//			'created' => ['$gte' => new UTCDateTime((time() - $this->config->word_stats->window) * 1000)],
//		]);

//		$tweets = (new Tweets($this->config->mongo_db))->find([
//			'entities.hashtags.0.text' => ['$gt' => ''],
//			'created_at'               => ['$gt' => new UTCDateTime(strtotime('10 seconds ago') * 1000)],
//		]);
//
//		$t   = 0;
//		$obj = new Structure\Tweet();
//		foreach ($tweets as $tweet) {
//			$obj->assign($tweet);
//			StdIo::phpOut($obj->toArray());
//			$t++;
//		}
//		StdIo::outln($t);
	}
}
