<?php

class GetTask extends \Phalcon\Cli\Task
{
	public function mainAction()
	{
		echo 'Get what?';
	}

	public function VersionAction()
	{
		cout($this->config->version);
	}

	public function pidPathAction()
	{
		cout($this->config->process->path);
	}

	public function hashtagsAction()
	{
		print_r(array_slice((new Tally\TopList\HashTags($this->mongo))->get($this->config->word_stats), 0, 25));
	}

	public function textwordsAction()
	{
		print_r(array_slice((new Tally\TopList\Text($this->mongo))->get($this->config->word_stats), 0, 25));
	}

}
