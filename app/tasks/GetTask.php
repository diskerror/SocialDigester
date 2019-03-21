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
		StdIo::outln($this->config->caches['index']->back->cacheDir);
	}

	/**
	 * Display JSON of Twitter hashtag statistics.
	 */
	public function hashtagsAction()
	{
		StdIo::jsonOut(array_slice(Code\Tally\TopList::getHashtags($this->config->word_stats)->arr, 0, 25));
	}

	/**
	 * Display JSON of Twitter tweet word count statistics.
	 */
	public function textwordsAction()
	{
		StdIo::jsonOut(array_slice(Code\Tally\TopList::getText($this->config->word_stats)->arr, 0, 25));
	}

	/**
	 * Display a summary of all current tweets.
	 */
	public function summaryAction()
	{
		StdIo::outln(implode("\n\n", Code\Summary::get($this->config->wordStats, $this->mongodb)));
	}

	/**
	 * Store a snapshot of the current Twitter activity.
	 */
	public function snapshotAction()
	{
		StdIo::outln(Code\Snapshots::make($this->config));
	}
}
