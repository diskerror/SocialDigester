<?php

use Logic\PidHandler;
use MongoDB\BSON\UTCDateTime;
use Resource\MongoCollection;
use Resource\Tweets;
use Service\StaticTimer;
use Service\StdIo;

class TweetsTask extends TaskMaster
{
	/**
	 * Start process of receiving tweets.
	 */
	public function getAction()
	{
		Logic\ConsumeTweets::exec($this->config);
	}

	/**
	 * Stop tweet consumption.
	 */
	public function stopAction()
	{
		$pidHandler = new PidHandler($this->config->process);
		if ($pidHandler->removeIfExists()) {
			StdIo::outln('Running process was stopped.');
		} else {
			StdIo::outln('Process was not running.');
		}
	}

	/**
	 * Place for test code.
	 */
	public function testAction()
	{
		StaticTimer::start('test');
		for ($i = 0; $i < 10000; ++$i) {
			$tmp = time() - 60;
//			$tmp = strtotime('60 seconds ago');
			unset($tmp);
		}
		StdIo::outln(StaticTimer::elapsed('test'));

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

	/**
	 * Returns 1 if consume process is running, zero if not.
	 */
	public function runningAction()
	{
		StdIo::outln(StaticTimer::elapsed('consume') < 6 ? 1 : 0);
	}
}
