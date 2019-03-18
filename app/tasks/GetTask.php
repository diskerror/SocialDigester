<?php

use Service\StdIo;

class GetTask extends TaskMaster
{
	/**
	 * Get current version.
	 */
	public function versionAction()
	{
		StdIo::outln($this->config->version);
	}

	/**
	 * Get PID path.
	 */
	public function pidPathAction()
	{
		StdIo::outln($this->config->process->path);
	}

	/**
	 * Get cache path.
	 */
	public function cachePathAction()
	{
		StdIo::outln($this->config->index_cache->back->cacheDir);
	}

	/**
	 * Display JSON of Twitter hashtag statistics.
	 */
	public function hashtagsAction()
	{
		StdIo::outln(
			json_encode(
				array_slice(Code\Tally\TopList::getHashtags($this->config->word_stats)->arr, 0, 25),
				JSON_PRETTY_PRINT
			)
		);
	}

	/**
	 * Display JSON of Twitter tweet word count statistics.
	 */
	public function textwordsAction()
	{
		StdIo::outln(
			json_encode(
				array_slice(Code\Tally\TopList::getText($this->config->word_stats)->arr, 0, 25),
				JSON_PRETTY_PRINT
			)
		);
	}

	/**
	 * Display a summary of all current tweets.
	 */
	public function summaryAction()
	{
		$summary = Code\Summary::get($this->config->word_stats);
		StdIo::out(implode("\n\n", $summary));
	}

	/**
	 * Store a snapshot of the current Twitter activity.
	 */
	public function snapshotAction()
	{
		StdIo::outln(Code\Snapshots::make($this->config));
	}
}
