<?php

class TweetsTask extends \Phalcon\Cli\Task
{
	private $_pidHandler;

	public function mainAction()
	{
		cout(PHP_EOL . 'And do what?');
	}

	public function getAction()
	{
		$stream = new TwitterClient\Stream($this->config->twitter->auth);
// 		$logger = LoggerFactory::getFileLogger(APP_PATH . '/' . $this->config->process->name . '.log');
		$logger = LoggerFactory::getStreamLogger();

		ConsumeTweets::exec($this->db->tweets, $stream, $this->config->twitter->track, $logger, $this->_getPidHandler());
	}

	protected function _getPidHandler() : PidHandler
	{
		if (!isset($this->_pidHandler)) {
			$this->_pidHandler = new PidHandler($this->config->process);
		}

		return $this->_pidHandler;
	}

	public function stopAction()
	{
		if ($this->_getPidHandler()->removeIfExists()) {
			cout('Process was stopped.');
		}
		else {
			cout('Process was not running.');
		}
	}

	public function indexAction()
	{
		//	These only needs to be run on a new collection.
		$this->db->tweets->createIndex(
			['created_at' => 1],
			['expireAfterSeconds' => $this->config->tweets_expire]
		);

		$this->db->tweets->createIndex(
			['entities.hashtags.0.text' => 1]
		);

		$this->db->snapshots->createIndex(
			['created' => 1]
		);
	}

	public function testAction()
	{
		$tweets = $this->db->tweets->find([
// 			'entities.hashtags.0.text' => ['$gt' => ''],
			'created_at' => ['$gt' => new \MongoDB\BSON\UTCDateTime(strtotime('10 seconds ago') * 1000)],
		]);

		$t = 0;
		foreach ($tweets as $tweet) {
			$tweet = new Tweet\Tweet($tweet);
			print_r($tweet->toArray());
			$t++;
		}
		echo $t;
	}

	public function runningAction()
	{
		$t = $this->db->tweets->count([
			'created_at' => ['$gt' => new \MongoDB\BSON\UTCDateTime(strtotime('4 seconds ago') * 1000)],
		]);

		cout($t===0 ? 0 : 1);
	}

}
