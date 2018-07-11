<?php

class TweetsTask extends \Phalcon\Cli\Task
{
	private $_pidHandler;

	public function mainAction()
	{
		fwrite(STDOUT, 'And do what?');
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
			fwrite(STDOUT, 'Process was stopped.');
		}
		else {
			fwrite(STDOUT, 'Process was not running.');
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
			'created_at' => ['$gt' => date('Y-m-d H:i:s', strtotime('10 seconds ago'))],
		]);

		$t = 0;
		$obj = new Tweet\Tweet();
		foreach ($tweets as $tweet) {
			$obj->assign($tweet);
			print_r($obj->toArray());
			$t++;
		}
		echo $t;
	}

	public function runningAction()
	{
		$t = $this->db->tweets->count([
			'created_at' => ['$gt' => date('Y-m-d H:i:s', strtotime('4 seconds ago'))],
		]);

		fwrite(STDOUT, $t===0 ? 0 : 1);
	}

}
