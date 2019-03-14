<?php

use MongoDB\BSON\UTCDateTime;

class TweetsTask extends Cli
{
	public function mainAction()
	{
		self::println('And do what?');
	}

	public function getAction()
	{
		Code\ConsumeTweets::exec($this->config->twitter);
	}

	public function stopAction()
	{
		$pidHandler = new Code\PidHandler($this->config->process);
		if ($pidHandler->removeIfExists()) {
			self::println('Process was stopped.');
		}
		else {
			self::println('Process was not running.');
		}
	}

	public function testAction()
	{
		$tweets = (new Resource\Tweets())->find([
 			'entities.hashtags.0.text' => ['$gt' => ''],
			'created_at' => ['$gt' => date('Y-m-d H:i:s', strtotime('10 seconds ago'))],
		]);

		$t = 0;
		foreach ($tweets as $tweet) {
			self::println(json_encode($tweet, JSON_PRETTY_PRINT));
			$t++;
		}
		self::println($t);
	}

	public function runningAction()
	{
		$t = (new Resource\Tweets())->count([
//			'created_at' => ['$gt' => date('Y-m-d H:i:s', strtotime('4 seconds ago'))],
			'created_at' => ['$gt' => new UTCDateTime(strtotime('4 seconds ago')*1000)],
		]);

		self::println(($t===0 ? 0 : 1));
	}
}
