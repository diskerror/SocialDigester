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

	public function cachePathAction()
	{
		cout($this->config->index_cache->back->cacheDir);
	}

	public function hashtagsAction()
	{
		print_r(array_slice((new Tally\TopList\HashTags($this->tweets))->get($this->config->word_stats), 0, 25));
	}

	public function textwordsAction()
	{
		print_r(array_slice((new Tally\TopList\Text($this->tweets))->get($this->config->word_stats), 0, 25));
	}

	public function summaryAction()
	{
		$summary = new GenerateSummary($this->tweets);
		cout(implode("\n\n", $summary->exec($this->config->word_stats)));
	}

}
