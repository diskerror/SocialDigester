<?php

use Logic\ConfigFactory;
use Service\StdIo;

class GetTask extends TaskMaster
{
	/**
	 * Get current version.
	 */
	public function versionAction()
	{
		StdIo::outln(ConfigFactory::get()->version);
	}

	/**
	 * Get PID path.
	 */
	public function pidPathAction()
	{
		StdIo::outln(ConfigFactory::get()->process->path);
	}

	/**
	 * Get cache path.
	 */
	public function cachePathAction()
	{
		StdIo::outln(ConfigFactory::get()->cache->index->back->dir);
	}

	/**
	 * Display PHP array of Twitter hashtag statistics once per tweet.
	 */
	public function hashtagsAction()
	{
		StdIo::phpOut(array_slice(Logic\Tally\TopList::getHashtags(ConfigFactory::get()), 0, 25));
	}

	/**
	 * Display PHP array of all hashtag statistics.
	 */
	public function hashtagsAllAction()
	{
		StdIo::phpOut(Logic\Tally\TopList::getHashtagsFromTallies(ConfigFactory::get()));
	}

	/**
	 * Display PHP array of Twitter tweet word count statistics.
	 */
	public function textwordsAction()
	{
		StdIo::phpOut(array_slice(Logic\Tally\TopList::getText(ConfigFactory::get())->toArray(), 0, 32));
	}

	/**
	 * Display a summary of all current tweets.
	 */
	public function summaryAction()
	{
		StdIo::outln('');
		StdIo::outln(implode("\n\n", Logic\Summary::get(ConfigFactory::get()->mongo_db)));
		StdIo::outln('');
	}

	/**
	 * Store a snapshot of the current Twitter activity.
	 */
	public function snapshotAction()
	{
//		StdIo::outln(Logic\Snapshots::make($this->mongodb, ConfigFactory::get()));
	}
}
