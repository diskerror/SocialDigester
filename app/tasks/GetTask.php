<?php

use Logic\TextGroup;
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
		StdIo::outln($this->config->cache->index->back->dir);
	}

	/**
	 * Display PHP array of Twitter hashtag statistics once per tweet.
	 */
	public function hashtagsAction()
	{
		$totals = Logic\Tally\Hashtags::get($this->config->mongo_db, 60);
		StdIo::phpOut(array_slice(TextGroup::normalize($totals), 0, 20));
	}

	/**
	 * Display PHP array of all hashtag statistics.
	 */
	public function hashtagsAllAction()
	{
		StdIo::phpOut(Logic\Tally\TopList::getHashtagsFromTallies($this->config));
	}

	/**
	 * Display PHP array of Twitter tweet word count statistics.
	 */
	public function textwordsAction()
	{
		StdIo::phpOut(array_slice(Logic\Tally\TopList::getText($this->config)->toArray(), 0, 32));
	}

	/**
	 * Display a summary of all current tweets.
	 */
	public function summaryAction()
	{
		StdIo::outln('');
		StdIo::outln(implode("\n\n", Logic\Summary::get($this->config->mongo_db)));
		StdIo::outln('');
	}

	/**
	 * Store a snapshot of the current Twitter activity.
	 */
	public function snapshotAction()
	{
//		StdIo::outln(Logic\Snapshots::make($this->mongodb, $this->config));
	}
}
