<?php

class GetTask extends \Phalcon\Cli\Task
{
	public function mainAction()
	{
		echo 'Get what?';
	}

	public function versionAction()
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
		print_r(array_slice(Tally\TopList::getHashtags($this->db->tweets, $this->config->word_stats)->arr, 0, 25));
	}

	public function textwordsAction()
	{
		print_r(array_slice(Tally\TopList::getText($this->db->tweets, $this->config->word_stats)->arr, 0, 25));
	}

	public function summaryAction()
	{
		$summary = Summary::get($this->db->tweets, $this->config->word_stats);
		cout(implode("\n\n", $summary));
	}

	public function snapshotAction()
	{
		cout(Snapshot::make($this->db, $this->config));
	}
}
