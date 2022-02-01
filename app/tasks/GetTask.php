<?php

use Logic\TextGroup;
use Logic\UserNameGroup;
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
		$totals = Logic\Tally\HashtagsAll::get($this->config->mongo_db, 180);
		StdIo::phpOut(array_slice(TextGroup::normalize($totals), 0, 20));
	}

	/**
	 * Display PHP array of Twitter tweet word count statistics.
	 */
	public function textwordsAction()
	{
		$totals = Logic\Tally\TextWords::get($this->config->mongo_db, 180);
		StdIo::phpOut(array_slice(TextGroup::normalize($totals, 'strtolower'), 0, 32));
	}

	/**
	 * Display PHP array of Twitter tweet word count statistics.
	 */
	public function userMentionsAction()
	{
		$um = Logic\Tally\UserMentions::get($this->config->mongo_db, 180);
		StdIo::phpOut(array_slice(Logic\UserNameGroup::normalize($um, 'strtolower'), 0, 32));
	}

	/**
	 * Display a summary of all current tweets.
	 */
	public function summaryAction()
	{
		StdIo::outln();
		StdIo::outln(implode("\n\n", Logic\Summary::get($this->config->mongo_db)));
		StdIo::outln();
	}

	public function usersAction()
	{
		$totals = Logic\Tally\Users::get($this->config->mongo_db, 600);
		StdIo::phpOut(array_slice(UserNameGroup::normalize($totals), 0, 10));
	}

	public function retweetsAction()
	{
		$totals = Logic\Tally\Retweets::get($this->config->mongo_db, 600);
		StdIo::phpOut(array_slice(UserNameGroup::normalize($totals), 0, 10));
	}

	/**
	 * Store a snapshot of the current Twitter activity.
	 */
	public function snapshotAction()
	{
//		StdIo::outln(Logic\Snapshots::make($this->mongodb, $this->config));
	}
}
