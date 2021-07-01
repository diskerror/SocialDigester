<?php

use Logic\ConfigFactory;
use MongoDB\BSON\UTCDateTime;

class TweetsTask extends Cli
{
	public function mainAction()
	{
		self::println('And do what?');
	}

	public function getAction()
	{
		Logic\ConsumeTweets::exec(ConfigFactory::get());
	}

	public function stopAction()
	{
		$pidHandler = new Logic\PidHandler(ConfigFactory::get()->process);
		if ($pidHandler->removeIfExists()) {
			self::println('Running process was stopped.');
		}
		else {
			self::println('Process was not running.');
		}
	}

	public function testAction()
	{
		$tweets = (new Resource\Tweets(ConfigFactory::get()->mongo_db))->find([
 			'entities.hashtags.0.text' => ['$gt' => ''],
			'created_at' => ['$gt' => new UTCDateTime(strtotime('10 seconds ago') * 1000)],
		]);

		$t = 0;
		$obj = new Structure\Tweet();
		foreach ($tweets as $tweet) {
			$obj->assign($tweet);
			var_export($obj->toArray());
			$t++;
		}
		self::println($t);
	}

	public function runningAction()
	{
		$t = (new Resource\Tweets(ConfigFactory::get()->mongo_db))->count([
			'created_at' => ['$gt' => new UTCDateTime(strtotime('10 seconds ago') * 1000)],
		]);

		self::println($t===0?0:1);
	}
}
