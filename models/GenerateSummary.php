<?php

class GenerateSummary
{
	protected $_twit;

	/**
	 * @param MongoDB\Client       $mongo
	 */
	function __construct(MongoDB\Client $mongo)
	{
		$this->_twit = $mongo->feed->twitter;
	}

	/**
	 * Generate summary of tweet texts.
	 *
	 * @param Phalcon\Config $config
	 *
	 * @return array
	 */
	public function exec(Phalcon\Config $config)
	{
		$tweets = $this->_twit->find([
			'text'       => ['$gt' => ''],
			'created_at' => ['$gt' => new MongoDB\BSON\UTCDateTime(strtotime($config->window . ' seconds ago') * 1000)],
		]);

		$text = '';
		foreach ($tweets as $tweet) {
			if (preg_match('/(^039|^rt$)/i', $tweet['text'])) {
				continue;
			}

			$text .= $tweet->text . "\n";
		}

		$tr = new PhpScience\TextRank\TextRankFacade();
		$tr->setStopWords(new PhpScience\TextRank\Tool\StopWords\English());
		return $tr->summarizeTextBasic($text);
	}

}
