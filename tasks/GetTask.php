<?php

class GetTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
        echo 'Get what?';
    }

    public function VersionAction()
    {
        echo $this->config->version;
    }

    public function pidPathAction()
    {
        echo $this->config->process->path;
    }

}
