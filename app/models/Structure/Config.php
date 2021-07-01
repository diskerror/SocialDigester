<?php


namespace Structure;


use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;
use Structure\Config\Caches;
use Structure\Config\Mongo;
use Structure\Config\Process;
use Structure\Config\Twitter;
use Structure\Config\WordStats;

/**
 * Class Config
 *
 * @param $version
 * @param $mongo_db
 * @param $tweets_expire
 * @param $word_stats
 * @param $twitter
 * @param $process
 * @param $caches
 *
 * @package Structure
 *
 */
class Config extends TypedClass
{
	protected $version       = [TString::class];
	protected $mongo_db      = [Mongo::class];
	protected $tweets_expire = 600;
	protected $word_stats    = [WordStats::class];
	protected $twitter       = [Twitter::class];
	protected $process       = [Process::class];
	protected $cache         = [Caches::class];
}
