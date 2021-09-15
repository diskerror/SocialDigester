<?php

use Logic\PidHandler;
use MongoDB\BSON\UTCDateTime;
use Resource\MongoCollection;
use Resource\Tweets;
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
		$tallies = (new Tallies($this->config->mongo_db))->find([
			'created' => ['$gte' => new UTCDateTime((time() - $this->config->word_stats->window) * 1000)],
		]);

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
		$t = (new Resource\Tweets($this->config->mongo_db))->count([
			'created_at' => ['$gt' => new UTCDateTime(strtotime('6 seconds ago') * 1000)],
		]);

		StdIo::outln(($t === 0 ? 0 : 1));
	}
}
