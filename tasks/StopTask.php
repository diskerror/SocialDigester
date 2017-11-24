<?php

class StopTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
    	cout(PHP_EOL . 'Stop what?');
	}

    public function tweetsAction()
    {
    	if ( PidHandler::removeIfExists('tweets') ) {
    		cout('Process was stopped.');
    	}
    	else {
    		cout('Process was not running.');
    	}
    }

}
