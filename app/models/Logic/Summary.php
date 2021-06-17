<?php

namespace Logic;

use Ds\Vector;
use MongoDB\BSON\UTCDateTime;
use PhpScience\TextRank\Tool\Graph;
use PhpScience\TextRank\Tool\Parser;
use PhpScience\TextRank\Tool\Score;
use PhpScience\TextRank\Tool\StopWords\English;
use PhpScience\TextRank\Tool\Summarize;
use Resource\Tweets;

final class Summary
{
	private const HISTORY_WINDOW = '90';
	private const RETURN_COUNT = 3;

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
					['$gt' => new UTCDateTime(strtotime(self::HISTORY_WINDOW . ' seconds ago') * 1000)],
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
		$parser->setMinimumWordLength(3);
		$parser->setRawText($text);
		$parser->setStopWords(new English());

		$text = $parser->parse();

		$graph = new Graph();
		$graph->createGraph($text);

		$scores = (new Score())->calculate($graph, $text);

		$summaries = new Vector();

		$summaries->push(...(new Summarize())->getSummarize(
			$scores,
			$graph,
			$text,
			16,	//	how many words to test
			64,	//	size of array to return
			Summarize::GET_ALL_IMPORTANT
		));

		$summary = '';
		$subSummaries = [];
		$summaryCount = 0;
		$outputArr    = [];
		while (!$summaries->isEmpty()) {
			//	remove leading "@user: " if any
			$summary = preg_replace('/^@\w+: /', '', $summaries->pop());
			$sub = substr($summary, 0, 64);
			if (in_array($sub, $subSummaries, true)) {
				continue;
			}

			$subSummaries[] = $sub;
			$outputArr[]    = $summary;

			if (++$summaryCount >= self::RETURN_COUNT) {
				break;
			}
		}

		return $outputArr;
	}

}
