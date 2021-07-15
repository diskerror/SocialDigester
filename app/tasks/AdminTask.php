<?php

use Logic\ConfigFactory;
use MongoDB\BSON\UTCDateTime;
use Service\StdIo;

class AdminTask extends TaskMaster
{
	/**
	 * Displays the rate at which tweets are being consumed.
	 */
	public function rateAction()
	{
		$mongo_db = ConfigFactory::get()->mongo_db;

		$cnt = (new Resource\Tweets($mongo_db))->count([
			'created_at' => ['$gt' => new UTCDateTime(strtotime('20 seconds ago') * 1000)],
		]);

		StdIo::outln('Tweets are being received at a rate of ' . $cnt / 20 . ' per second.');
	}

	/**
	 * Display the current aggregate configuration.
	 */
	public function showConfigAction()
	{
		$config = ConfigFactory::get();
		StdIo::phpOut($config->toArray());
	}

	/**
	 * Reindex MongoDB collections.
	 */
	public function indexDbAction()
	{
		$config = ConfigFactory::get();

		//	These only needs to be run on a new collection.
		(new Resource\Tweets($config->mongo_db))->doIndex($config->tweets_expire);
		(new Resource\Tallies($config->mongo_db))->doIndex($config->tweets_expire);
		(new Resource\Snapshots($config->mongo_db))->doIndex();        //	snapshots don't expire
		(new Resource\Messages($config->mongo_db))->doIndex(36000);    //	ten hours
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
