<?php

use Logic\ConfigFactory;
use MongoDB\BSON\UTCDateTime;

class AdminTask extends Cli
{
	public function rateAction()
	{
		$cnt = (new Resource\Tweets(ConfigFactory::get()->mongo_db))->count([
			'created_at' => ['$gt' => new UTCDateTime(strtotime('20 seconds ago') * 1000)],
		]);

		fprintf(STDOUT, "Tweets are being received at a rate of %.2f per second.\n", $cnt / 20.0);
	}

	public function showConfigAction()
	{
		$config = ConfigFactory::get();
		self::println(json_encode($config->toArray(), JSON_PRETTY_PRINT));
	}

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
