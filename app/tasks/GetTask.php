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
		StdIo::jsonOut(array_slice(Logic\Tally\TopList::getHashtags($this->mongodb, $this->config->wordStats)->arr, 0, 32));
	}

	/**
	 * Display JSON of Twitter tweet word count statistics.
	 */
	public function textwordsAction()
	{
		StdIo::jsonOut(array_slice(Logic\Tally\TopList::getText($this->mongodb, $this->config->wordStats)->arr, 0, 32));
	}

	/**
	 * Display a summary of all current tweets.
	 */
	public function summaryAction()
	{
		StdIo::outln(implode("\n\n", Logic\Summary::get($this->mongodb, $this->config->wordStats)));
	}

	/**
	 * Store a snapshot of the current Twitter activity.
	 */
	public function snapshotAction()
	{
//		StdIo::outln(Logic\Snapshots::make($this->mongodb, $this->config));
	}
}
