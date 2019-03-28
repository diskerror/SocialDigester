<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/26/18
 * Time: 7:26 PM
 */

class AdminTask extends Cli
{
	public function rateAction()
	{
		$secs = 10;

		$t = (new Resource\Tweets())->count([
			'created_at' => ['$gt' => new \MongoDB\BSON\UTCDateTime(strtotime($secs . ' seconds ago') * 1000)],
		]);

		self::println('Tweets are being received at a rate of ' . $t / $secs . ' per second averaged over ' . $secs . ' seconds.');
	}

	public function showConfigAction()
	{
		self::println(json_encode($this->config, JSON_PRETTY_PRINT));
	}

	public function indexAction()
	{
		//	These only needs to be run on a new collection.
		(new Resource\Tweets())->doIndex($this->config->tweets_expire);
		(new Resource\Snapshots())->doIndex();		//	snapshots don't expire
		(new Resource\Messages())->doIndex(3600);	//	one hour
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
