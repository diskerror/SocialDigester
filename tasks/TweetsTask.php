<?php

class TweetsTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
    	cout(PHP_EOL . 'And do what?');
	}

    public function getAction()
    {
    	$tConfig = $this->config->twitter;

		$stream = new Twitter\Api\Stream( $tConfig->auth );
		$load	= new LoadTwitterStream( $this->mongo, $stream );

// 		$logger = new Logger(APP_PATH . '/' . $this->config->process->name . '.log');
		$logger = Logger::getStream();

		$load->exec( $tConfig->track, $logger, $this->pidHandler);
    }

    public function stopAction()
    {
    	if ( $this->pidHandler->removeIfExists() ) {
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
