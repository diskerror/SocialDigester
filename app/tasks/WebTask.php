<?php

use Logic\ConfigFactory;

/**
 * Class WebTask
 *
 * Great for debugging the core calls used by a web controller class.
 *
 */

class WebTask extends Cli
{
	public function mainAction()
	{
		self::println('And do what?');
	}

	public function tagCloudAction()
	{
		print_r(Logic\Tally\TagCloud::getHashtagsFromTallies(ConfigFactory::get())->toArray());
	}

	public function tagCloudAllAction()
	{
		print_r(Logic\Tally\TagCloud::getAllHashtagsFromTallies(ConfigFactory::get())->toArray());
	}

	public function textWordsAction()
	{
		print_r(Logic\Tally\TagCloud::getText(ConfigFactory::get())->toArray());
	}

	public function userMentionsAction()
	{
		print_r(Logic\Tally\TagCloud::getUserMentionsFromTallies(ConfigFactory::get())->toArray());
	}

	public function summaryAction()
	{
		print_r(Logic\Summary::get(ConfigFactory::get()->mongo_db));
	}

}
