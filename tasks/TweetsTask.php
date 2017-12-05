<?php

class TweetsTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
    	cout(PHP_EOL . 'And do what?');
	}

    public function getAction()
    {
		$stream = new Twitter\Api\Stream( $this->config->twitter_auth );
		$load	= new LoadTwitterStream( $this->mongo, $stream );

// 		$logger = new Logger(APP_PATH . '/tweets.log');
		$logger = Logger::getStream();

		$load->filter( $this->config->tracking_data, $logger );
    }

    public function stopAction()
    {
    	if ( PidHandler::removeIfExists('tweets') ) {
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
