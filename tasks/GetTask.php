<?php

use \Diskerror\Utilities\Registry;

class GetTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
    	cout(PHP_EOL . 'Get what?');
	}

    public function tweetsAction()
    {
// 		$logger = new Logger(APP_PATH . '/tweets.log');
		$logger = Logger::getStream();

		$config = $this->getDI()->get('config');

		LoadTwitterStream::filter((array)$config->twitter, (array)$config->tracking_data, $logger );
    }

    public function testAction()
    {
    }

}
