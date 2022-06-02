<?php

namespace Logic;

use Structure\Config;
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
	 *
	 * @return void
	 */
	public static function pre(Tweet $tweet, Tally $tally): void;

	/**
	 * @param Config $config
	 * @param int    $window
	 *
	 * @return mixed
	 */
	public static function get(Config $config, int $window);
}
