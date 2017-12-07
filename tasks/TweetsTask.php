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
		$stream = new Twitter\Api\Stream( $this->config->twitter->auth );
		$load	= new LoadTwitterStream( $this->mongo, $stream );

// 		$logger = new Logger(APP_PATH . '/' . $this->config->process->name . '.log');
		$logger = Logger::getStream();

		$load->exec( $this->config->twitter->track, $logger, $this->_getPidHandler());
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

	public function testAction()
	{
		cout(Phalcon\Version::get());
	}

}
