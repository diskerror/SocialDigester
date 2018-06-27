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
		$summary = GenerateSummary::exec($this->db->tweets, $this->config->word_stats);
		cout(implode("\n\n", $summary));
	}

	public function snapshotAction()
	{
		$snap = new Snapshot([
			'id_'      => time(),
			'tagCloud' => Tally\TagCloud::getHashtags($this->db->tweets, $this->config->word_stats),
		]);
		$this->db->snapshots->insertOne($snap->getArrForMongo());
		cout($snap->id_);
	}
}
