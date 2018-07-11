<?php

class GetTask extends \Phalcon\Cli\Task
{
	public function mainAction()
	{
		fwrite(STDOUT, 'Get what?');
	}

	public function versionAction()
	{
		fwrite(STDOUT, $this->config->version);
	}

	public function pidPathAction()
	{
		fwrite(STDOUT, $this->config->process->path);
	}

	public function cachePathAction()
	{
		fwrite(STDOUT, $this->config->index_cache->back->cacheDir);
	}

	public function hashtagsAction()
	{
		fwrite(STDOUT, print_r(array_slice(Tally\TopList::getHashtags($this->db->tweets, $this->config->word_stats)->arr, 0, 25), true));
	}

	public function textwordsAction()
	{
		fwrite(STDOUT, print_r(array_slice(Tally\TopList::getText($this->db->tweets, $this->config->word_stats)->arr, 0, 25), true));
	}

	public function summaryAction()
	{
		$summary = Summary::get($this->db->tweets, $this->config->word_stats);
		fwrite(STDOUT, implode("\n\n", $summary));
	}

	public function snapshotAction()
	{
		fwrite(STDOUT, Snapshot::make($this->db, $this->config));
	}
}
