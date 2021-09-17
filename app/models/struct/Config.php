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
 * @param TString   $userConfigName
 * @param TString   $version
 * @param Mongo     $mongo_db
 * @param integer   $tweets_expire
 * @param WordStats $word_stats
 * @param Twitter   $twitter
 * @param Process   $process
 * @param Caches    $caches
 *
 * @package Structure
 *
 */
class Config extends TypedClass
{
	protected $userConfigName = [TString::class];
	protected $version        = [TString::class];
	protected $mongo_db       = [Mongo::class];
	protected $word_stats     = [WordStats::class];
	protected $twitter        = [Twitter::class];
	protected $process        = [Process::class];
	protected $cache          = [Caches::class];
}
