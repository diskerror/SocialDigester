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
 * @property string $userConfigName
 * @property string $version
 * @property Mongo $mongo_db
 * @property WordStats $word_stats
 * @property Twitter $twitter
 * @property Process $process
 * @property Caches $cache
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
