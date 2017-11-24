<?php

class QueryTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
    	cout(PHP_EOL . 'Query what?');
	}

    public function hashtagsAction()
    {
		$twitDb = $this->di->get('mongo')->feed->twitter;

		$hashtags = $twitDb->find([
			'hashtags' => ['$gt' => '']
		], [
			'projection' => ['hashtags' => 1]
		]);

		$tally = [];
		foreach ( $hashtags as $ht ) {
			foreach ( $ht['hashtags'] as $h ) {
				$h = strtolower($h);
				if ( array_key_exists( $h, $tally ) ) {
					++$tally[$h];
				}
				else {
					$tally[$h] = 1;
				}
			}
		}

		asort($tally, SORT_NUMERIC);
		print_r($tally);
    }

    public function wordsAction()
    {
		$twitDb = $this->di->get('mongo')->feed->twitter;

		$hashtags = $twitDb->find([
			'words' => ['$gt' => '']
		], [
			'projection' => ['words' => 1]
		]);

		$tally = [];
		foreach ( $hashtags as $ht ) {
			foreach ( $ht['words'] as $h ) {
				if ( array_key_exists( $h, $tally ) ) {
					++$tally[$h];
				}
				else {
					$tally[$h] = 1;
				}
			}
		}

		asort($tally, SORT_NUMERIC);
		print_r($tally);
    }

    public function pairsAction()
    {
		$twitDb = $this->di->get('mongo')->feed->twitter;

		$hashtags = $twitDb->find([
			'pairs' => ['$gt' => '']
		], [
			'projection' => ['pairs' => 1]
		]);

		$tally = [];
		foreach ( $hashtags as $ht ) {
			foreach ( $ht['pairs'] as $h ) {
				if ( array_key_exists( $h, $tally ) ) {
					++$tally[$h];
				}
				else {
					$tally[$h] = 1;
				}
			}
		}

		asort($tally, SORT_NUMERIC);
		print_r($tally);
    }

}
