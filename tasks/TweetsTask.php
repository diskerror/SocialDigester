<?php

class TweetsTask extends \Phalcon\Cli\Task
{
	private $_pidHandler;

	public function mainAction()
	{
		cout(PHP_EOL . 'And do what?');
	}

	protected function _getPidHandler()
	{
		if( !isset($this->_pidHandler) ) {
			$this->_pidHandler = new PidHandler($this->config->process);
		}

		return $this->_pidHandler;
	}

	public function getAction()
	{
		$stream = new TwitterClient\Stream( $this->config->twitter->auth );
		$consumer = new ConsumeTweets( $this->mongo, $stream );

// 		$logger = LoggerFactory::getFileLogger(APP_PATH . '/' . $this->config->process->name . '.log');
		$logger = LoggerFactory::getStreamLogger();

		$consumer->exec( $this->config->twitter->track, $logger, $this->_getPidHandler());
	}

	public function stopAction()
	{
		if ( $this->_getPidHandler()->removeIfExists() ) {
			cout('Process was stopped.');
		}
		else {
			cout('Process was not running.');
		}
	}

	public function indexAction()
	{
		//	These only needs to be run on a new collection.
		$this->mongo->feed->twitter->createIndex(
			['created_at' => 1],
			['expireAfterSeconds' => $this->config->mongo_expire]
		);

		$this->mongo->feed->twitter->createIndex(
			['entities.hashtags.0.text' => 1]
		);
	}

	public function testAction()
	{
		$tweets = $this->mongo->feed->twitter->find([
// 			'entities.hashtags.0.text' => ['$gt' => ''],
			'created_at' => ['$gt' => new \MongoDB\BSON\UTCDateTime( strtotime('10 seconds ago')*1000 )]
		]);

		$t = 0;
		foreach ( $tweets as $tweet ) {
			$tweet = new Tweet($tweet);
			print_r( $tweet->getSpecialObj(['dateToBsonDate'=>false]) );
			$t++;
		}
		echo $t;
	}

}
