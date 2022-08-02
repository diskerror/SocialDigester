<?php

namespace Logic;

use Ds\Vector;
use MongoDB\BSON\UTCDateTime;
use PhpScience\TextRank\Tool\Graph;
use PhpScience\TextRank\Tool\Parser;
use PhpScience\TextRank\Tool\Score;
use PhpScience\TextRank\Tool\Summarize;
use Resource\MongoCollection;
use Service\StopWords;
use Structure\Config;

final class Summary
{
	private function __construct() { }

	/**
	 * Generate summary of tweet texts.
	 *
	 * @param Config $config
	 * @param int    $window
	 * @param int    $quantity
	 *
	 * @return array
	 */
	public static function get(Config $config, int $window, int $quantity): array
	{
		ini_set('memory_limit', 256 * 1024 * 1024);

		$tweets = (new MongoCollection($config, 'tweets'))->find(
			[
				'created_at' => ['$gt' => new UTCDateTime((time() - $window) * 1000)],
			],
			[
				'sort'       => ['created_at' => -1],
				'limit'      => 600,
				'projection' => ['text' => 1],
			]
		);

		$text = '';
		foreach ($tweets as $tweet) {
			$text .= preg_replace('/^\\s*(@\w+: |^039 ?|^rt ?)(@\w+: |)/i', '',
					trim($tweet->text, "\x00..\x20")) . PHP_EOL;
		}

		unset($tweets, $tweet);

		$parser = new Parser();
		$parser->setMinimumWordLength(3);
		$parser->setRawText($text);
//        $parser->setStopWords(new English());
		$parser->setStopWords(new StopWords($config->configPath));

		$text = $parser->parse();

		unset($parser);

		$graph = new Graph();
		$graph->createGraph($text);

		$scores = (new Score())->calculate($graph, $text);

		$summaries = new Vector();

		$summaries->push(...(new Summarize())->getSummarize(
			$scores,
			$graph,
			$text,
			12,    //	how many words to test
			12,    //	size of array to return
			Summarize::GET_FIRST_IMPORTANT_AND_FOLLOWINGS
		));

		unset($graph, $scores);

		$subSummaries = [];
		$summaryCount = 0;
		$outputArr    = [];
		while (!$summaries->isEmpty()) {
			$summary = $summaries->pop();

			$sub = substr($summary, 0, 64);
			if (in_array($sub, $subSummaries, true)) {
				continue;
			}

			$subSummaries[] = $sub;
			$outputArr[]    = $summary;

			if (++$summaryCount >= $quantity) {
				break;
			}
		}

		return $outputArr;
	}

}
