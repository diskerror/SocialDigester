<?php

class TweetsTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
    	cout(PHP_EOL . 'And do what?');
	}

    public function getAction()
    {
		$config = $this->getDI()->get('config');

		$stream = new Twitter\Api\Stream( $config->twitter_auth );
		$load	= new LoadTwitterStream( $this->getDI()->getShared('mongo'), $stream );

// 		$logger = new Logger(APP_PATH . '/tweets.log');
		$logger = Logger::getStream();

		$load->filter( $config->tracking_data, $logger );
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
