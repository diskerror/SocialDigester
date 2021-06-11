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
		self::println( $this->config->index_cache->back->cacheDir);
	}

	public function hashtagsAction()
	{
		self::println(
			json_encode(
				array_slice(Logic\Tally\TopList::getHashtags($this->config->word_stats), 0, 25),
				JSON_PRETTY_PRINT
			)
		);
	}
	public function hashtagsAllAction()
	{
		self::println(
			json_encode(
				Logic\Tally\TagCloud::getHashtagsFromTallies($this->config->word_stats),
				JSON_PRETTY_PRINT
			)
		);
	}

	public function textwordsAction()
	{
		self::println(
			json_encode(
				array_slice(Logic\Tally\TopList::getText($this->config->word_stats)->toArray(), 0, 25),
				JSON_PRETTY_PRINT
			)
		);
	}

	public function summaryAction()
	{
		$summary = Logic\Summary::get();
		self::println(implode("\n\n", $summary));
	}

	public function snapshotAction()
	{
		self::println(Logic\Snapshots::make($this->config));
	}
}
