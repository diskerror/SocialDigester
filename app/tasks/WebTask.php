<?php

use Logic\ConfigFactory;
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
		StdIo::phpOut(TagCloud::getHashtagsFromTallies(ConfigFactory::get())->toArray());
	}

	public function tagCloudAllAction()
	{
		StdIo::phpOut(TagCloud::getAllHashtagsFromTallies(ConfigFactory::get())->toArray());
	}

	public function textWordsAction()
	{
		StdIo::phpOut(TagCloud::getText(ConfigFactory::get())->toArray());
	}

	public function userMentionsAction()
	{
		StdIo::phpOut(TagCloud::getUserMentionsFromTallies(ConfigFactory::get())->toArray());
	}

	public function summaryAction()
	{
		StdIo::phpOut(Summary::get(ConfigFactory::get()->mongo_db));
	}

}
