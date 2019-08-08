<?php

class GetTask extends Cli
{
	public function mainAction()
	{
		self::println("Get what?");
	}

	public function versionAction()
	{
		self::println($this->config->version);
	}

	public function pidPathAction()
	{
		self::println($this->config->process->path);
	}

	public function cachePathAction()
	{
		self::println($this->config->index_cache->back->cacheDir);
	}

	public function hashtagsAction()
	{
//		$start = microtime(true);
		self::println(
			json_encode(Code\Tally\TopList::getHashtags($this->config->word_stats), JSON_PRETTY_PRINT)
		);
//		self::println((microtime(true) - $start) * 1000);
	}

	public function textwordsAction()
	{
		self::println(
			json_encode(Code\Tally\TopList::getText($this->config->word_stats), JSON_PRETTY_PRINT)
		);
	}

	public function summaryAction()
	{
		$summary = Code\Summary::get($this->config->word_stats);
		self::print(implode("\n\n", $summary));
	}

	public function snapshotAction()
	{
		self::println(Code\Snapshots::make($this->config));
	}
}
