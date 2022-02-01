<?php

namespace Logic;

use Structure\Config\Mongo;
use Structure\Tally;
use Structure\Tweet;

/**
 * TallyInterface
 */
interface TallyInterface
{
	/**
	 * @param Tweet $tweet
	 * @param Tally $tally
	 * @return mixed
	 */
	public static function pre(Tweet $tweet, Tally &$tally);

	/**
	 * @param Mongo $mongo_db
	 * @param int $window
	 * @return mixed
	 */
	public static function get(Mongo $mongo_db, int $window);
}
