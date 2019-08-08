<?php
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
		print_r(Code\Tally\TagCloud::getHashtagsFromTallies($this->config->word_stats)->toArray());
	}

	public function tagCloudAllAction()
	{
		print_r(Code\Tally\TagCloud::getAllHashtagsFromTallies($this->config->word_stats)->toArray());
	}

	public function textWordsAction()
	{
		print_r(Code\Tally\TagCloud::getText($this->config->word_stats)->toArray());
	}

	public function userMentionsAction()
	{
		print_r(Code\Tally\TagCloud::getUserMentionsFromTallies($this->config->word_stats)->toArray());
	}

	public function summaryAction()
	{
		print_r(Code\Summary::get());
	}

}
