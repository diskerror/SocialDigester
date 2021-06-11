<?php

namespace Logic;

use MongoDB\BSON\UTCDateTime;
use PhpScience\TextRank\Tool\Graph;
use PhpScience\TextRank\Tool\Parser;
use PhpScience\TextRank\Tool\Score;
use PhpScience\TextRank\Tool\StopWords\English;
use PhpScience\TextRank\Tool\Summarize;
use Resource\Tweets;

final class Summary
{
	private function __construct() { }

	/**
	 * Generate summary of tweet texts.
	 *
	 * @return array
	 */
	public static function get(): array
	{
		ini_set('memory_limit', 512 * 1024 * 1024);

		$tweets = (new Tweets())->getClient()->find(
			[
				'created_at' =>
					['$gt' => new UTCDateTime(strtotime('180 seconds ago') * 1000)],
			],
			[
				'sort'       => [
					'created_at' => -1,
				],
				'limit'      => 10000,
				'projection' => [
					'text' => 1,
				],
			]
		);

		$text = '';
		foreach ($tweets as $tweet) {
			if (preg_match('/(^039|^rt)/i', $tweet->text)) {
				$text .= substr($tweet->text, 3) . "\n";
			}
			else {
				$text .= $tweet->text . "\n";
			}
		}

		$parser = new Parser();
		$parser->setMinimumWordLength(2);
		$parser->setRawText($text);
		$parser->setStopWords(new English());

		$text = $parser->parse();

		$graph = new Graph();
		$graph->createGraph($text);

		$scores = (new Score())->calculate($graph, $text);

		$summaries = (new Summarize())->getSummarize(
			$scores,
			$graph,
			$text,
			10,
			100,
			Summarize::GET_ALL_IMPORTANT
		);

		$subSummaries = [];
		$summaryCount = 0;
		$outputArr    = [];
		foreach ($summaries as $summary) {
			$sub = substr($summary, 10, 30);
			if (in_array($sub, $subSummaries, true)) {
				continue;
			}

			$subSummaries[] = $sub;
			$outputArr[]    = $summary;

			if (++$summaryCount >= 3) {
				break;
			}
		}

		return $outputArr;
	}

}
