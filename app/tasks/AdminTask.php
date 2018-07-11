<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 6/26/18
 * Time: 7:26 PM
 */

class AdminTask extends \Phalcon\Cli\Task
{
	public function mainAction()
	{
		fwrite(STDOUT, Tweets::findFirst());
		return;
		$t = $this->mongo->tweets->count([
			'created_at' => ['$gt' => date('Y-m-d H:i:s', strtotime('10 seconds ago'))],
		]);

		fwrite(STDOUT, 'Tweets are being received at a rate of ' . $t / 10 . ' per second.');
	}

	public function checkRunningAction()
	{
//DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
//
//RUNNING=$(${DIR}/cli.php tweets running);
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
