<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/26/18
 * Time: 7:26 PM
 */

use Service\StdIo;

class AdminTask extends TaskMaster
{
	/**
	 * Displays the rate at which tweets are being consumed.
	 */
	public function rateAction()
	{
		$t = $this->mongodb->tweets->count([
			'created_at' => ['$gt' => new \MongoDB\BSON\UTCDateTime(strtotime('20 seconds ago') * 1000)],
		]);

		StdIo::outln('Tweets are being received at a rate of ' . $t / 20 . ' per second.');
	}

	/**
	 * Display the current aggregate configuration.
	 */
	public function showConfigAction()
	{
		StdIo::jsonOut($this->config);
	}

	/**
	 * Reindex MongoDB collections.
	 */
	public function indexAction()
	{
		foreach ($this->mongodb->getCollectionNames() as $collectionName) {
			$this->mongodb->doIndex($collectionName);
		}
	}

	public function handleRunningAction()
	{
//DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
//
//RUNNING=$(${DIR}/run admin rate);
//
//if [ "$RUNNING" -eq 0 ]
//then
//    ${DIR}/restart_tweets.sh
//fi
	}

	public function restartTweetsAction()
	{
//DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
//
//${DIR}/cli.php tweets stop
//
//PID_PATH=$(${DIR}/cli.php get pidPath);
//if [ ! -e "$PID_PATH" ]
//then
//    mkdir -pm 777 "$PID_PATH"
//fi
//
//CACHE_PATH=$(${DIR}/cli.php get cachePath);
//if [ ! -e "$CACHE_PATH" ]
//then
//    mkdir -pm 777 "$CACHE_PATH"
//fi
//
//sleep 1
//${DIR}/cli.php tweets get &
	}
}
