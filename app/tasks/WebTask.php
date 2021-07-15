<?php

use Logic\Summary;
use Logic\Tally\TagCloud;
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
		StdIo::phpOut(TagCloud::getHashtagsFromTallies($this->config)->toArray());
	}

	public function tagCloudAllAction()
	{
		StdIo::phpOut(TagCloud::getAllHashtagsFromTallies($this->config)->toArray());
	}

	public function textWordsAction()
	{
		StdIo::phpOut(TagCloud::getText($this->config)->toArray());
	}

	public function userMentionsAction()
	{
		StdIo::phpOut(TagCloud::getUserMentionsFromTallies($this->config)->toArray());
	}

	public function summaryAction()
	{
		StdIo::phpOut(Summary::get($this->config->mongo_db));
	}

}
