<?php

use Logic\ConfigFactory;

class GetTask extends Cli
{
	public function mainAction()
	{
		self::println("Get what?");
	}

	public function versionAction()
	{
		self::println(ConfigFactory::get()->version);
	}

	public function pidPathAction()
	{
		self::println(ConfigFactory::get()->process->path);
	}

	public function cachePathAction()
	{
		self::println(ConfigFactory::get()->cache->index->back->dir);
	}

	public function hashtagsAction()
	{
		self::println(
			json_encode(
				array_slice(Logic\Tally\TopList::getHashtags(ConfigFactory::get()), 0, 25),
				JSON_PRETTY_PRINT
			)
		);
	}
	public function hashtagsAllAction()
	{
		self::println(
			json_encode(
				Logic\Tally\TagCloud::getHashtagsFromTallies(ConfigFactory::get()),
				JSON_PRETTY_PRINT
			)
		);
	}

	public function textwordsAction()
	{
		self::println(
			json_encode(
				array_slice(Logic\Tally\TopList::getText(ConfigFactory::get())->toArray(), 0, 25),
				JSON_PRETTY_PRINT
			)
		);
	}

	public function summaryAction()
	{
		self::println('');
		self::println(implode("\n\n", Logic\Summary::get(ConfigFactory::get())));
		self::println('');
	}

	public function snapshotAction()
	{
		self::println(Logic\Snapshots::make(ConfigFactory::get()));
	}
}
