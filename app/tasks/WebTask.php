<?php

use Logic\TextGroup;
use Logic\UserNameGroup;
use Logic\WordCloud;
use Service\StdIo;

/**
 * Class WebTask
 *
 * Great for debugging the core calls used by a web controller class.
 *
 */
class WebTask extends TaskMaster
{
	public function tagCloudAction()
	{
		$totals  = Logic\Tally\Hashtags::get($this->config->mongo_db, 60);
		$grouped = TextGroup::normalize($totals);
		$obj     = WordCloud::build($grouped)->toArray();

		$obj = array_slice($obj, 0, 32);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		StdIo::phpOut($obj);
	}

	public function tagCloudAllAction()
	{
		$totals  = Logic\Tally\HashtagsAll::get($this->config->mongo_db, 180);
		$grouped = TextGroup::normalize($totals);
		$obj     = WordCloud::build($grouped)->toArray();

		$obj = array_slice($obj, 0, 32);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		StdIo::phpOut($obj);
	}

	public function textWordsAction()
	{
		$totals  = Logic\Tally\TextWords::get($this->config->mongo_db, 180);
		$grouped = TextGroup::normalize($totals, 'strtolower');
		$obj     = WordCloud::build($grouped)->toArray();

		$obj = array_slice($obj, 0, 48);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		StdIo::phpOut($obj);
	}

	public function userMentionsAction()
	{
		$um    = Logic\Tally\UserMentions::get($this->config->mongo_db, 180);
		$um    = TextGroup::normalize($um);
		$cloud = WordCloud::build($um);
		Logic\Tally\UserMentions::changeLink($cloud);

		$obj = array_slice($cloud->toArray(), 0, 48);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		StdIo::phpOut($obj);
	}

	public function retweetsAction()
	{
		$totals = Logic\Tally\Retweets::get($this->config->mongo_db, 1800);
		$totals = UserNameGroup::normalize($totals);
		$cloud  = WordCloud::build($totals);
		Logic\Tally\UserMentions::changeLink($cloud);

		$obj = array_slice($cloud->toArray(), 0, 18);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		StdIo::phpOut($obj);
	}

	public function usersAction()
	{
		$totals = Logic\Tally\Users::get($this->config->mongo_db, 1800);
		$totals = UserNameGroup::normalize($totals);
		$cloud  = WordCloud::build($totals);
		Logic\Tally\UserMentions::changeLink($cloud);

		$obj = array_slice($cloud->toArray(), 0, 18);
		ksort($obj, SORT_NATURAL | SORT_FLAG_CASE);

		StdIo::phpOut($obj);
	}

	public function summaryAction()
	{
		StdIo::phpOut(Logic\Summary::get($this->config->mongo_db, 60, 3));
	}

}
