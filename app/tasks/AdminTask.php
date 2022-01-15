<?php

use Logic\PidHandler;
use MongoDB\BSON\UTCDateTime;
use Service\StdIo;

class AdminTask extends TaskMaster
{
	/**
	 * Displays the rate at which tweets are being consumed.
	 */
	public function rateAction()
	{
		$cnt = (new Resource\Tweets($this->config->mongo_db))->count([
			'created_at' => ['$gt' => new UTCDateTime((time() - 20) * 1000)],
		]);

		StdIo::outln($cnt / 20);
	}

	/**
	 * Display the current aggregate configuration.
	 */
	public function showConfigAction()
	{
		StdIo::phpOut($this->config->toArray());
	}

	/**
	 * Reindex MongoDB collections.
	 */
	public function indexDbAction()
	{
		(new Resource\Tweets($this->config->mongo_db))->doIndex();
		(new Resource\Tallies($this->config->mongo_db))->doIndex();
		(new Resource\Snapshots($this->config->mongo_db))->doIndex();
		(new Resource\Messages($this->config->mongo_db))->doIndex();
	}

	/**
	 * Clear tweets and tallies collections.
	 */
	public function clearTweetsAction()
	{
		(new Resource\Tweets($this->config->mongo_db))->drop();
		(new Resource\Tallies($this->config->mongo_db))->drop();
	}

	/**
	 * Returns 1 if PID exists, 0 if not.
	 */
	public function pidExistsAction()
	{
		echo (new PidHandler($this->config->process))->exists() ? 1 : 0;
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
