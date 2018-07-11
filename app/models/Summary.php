<?php

use PhpScience\TextRank\Tool\Graph;
use PhpScience\TextRank\Tool\Parser;
use PhpScience\TextRank\Tool\Score;
use PhpScience\TextRank\Tool\StopWords\English;
use PhpScience\TextRank\Tool\Summarize;

final class Summary
{
	private function __construct() { }

	/**
	 * Generate summary of tweet texts.
	 *
	 * @param \MongoDB\Collection $tweetsCollection
	 * @param Phalcon\Config      $config
	 *
	 * @return array
	 */
	public static function get(\MongoDB\Collection $tweetsCollection, Phalcon\Config $config) : array
	{
		$tweets = $tweetsCollection->find(
			[
				'created_at' =>
					['$gt' => date('Y-m-d H:i:s', strtotime($config->window . ' seconds ago'))],
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
			if (preg_match('/(^039|^rt)/i', $tweet['text'])) {
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
			if (in_array($sub, $subSummaries)) {
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
