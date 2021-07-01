<?php

use MongoDB\BSON\UTCDateTime;
use Service\StdIo;

class TweetsTask extends TaskMaster
{
	public function getAction()
	{
		Logic\ConsumeTweets::exec(
			$this->stream,
			$this->config->track->toArray(),
			$this->pidHandler,
			$this->logger,
			$this->mongodb->tweets,
			$this->mongodb->messages
		);
	}

	public function stopAction()
	{
		if ($this->pidHandler->removeIfExists()) {
			StdIo::outln('Process was stopped.');
		}
		else {
			StdIo::outln('Process was not running.');
		}
	}

	public function testAction()
	{
		$tweets = $this->mongodb->tweets->find([
 			'entities.hashtags.0.text' => ['$gt' => ''],
			'created_at' => ['$gt' => date('Y-m-d H:i:s', strtotime('10 seconds ago'))],
		]);

		$t = 0;
		foreach ($tweets as $tweet) {
			StdIo::jsonOut($tweet);
			$t++;
		}
		StdIo::outln($t);
	}

	public function runningAction()
	{
		$t = $this->mongodb->tweets->count([
			'created_at' => ['$gt' => new UTCDateTime(strtotime('4 seconds ago')*1000)],
		]);

		StdIo::outln(($t===0 ? 0 : 1));
	}
}
