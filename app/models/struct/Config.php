<?php


namespace Structure;


use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;
use Structure\Config\Caches;
use Structure\Config\MongoDB;
use Structure\Config\Process;
use Structure\Config\Twitter;

/**
 * Class Config
 *
 * @property string $userConfigName
 * @property string $version
 * @property string $basePath
 * @property string $configPath
 * @property MongoDB $mongo_db
 * @property Twitter $twitter
 * @property Process $process
 * @property Caches $cache
 *
 */
class Config extends TypedClass
{
	protected $userConfigName = '.digester'; //	Name of the user's configuration file.
	protected $version        = 'v0.4';
	protected $basePath       = [TString::class];
	protected $configPath     = [TString::class];
	protected $mongo_db       = [MongoDB::class];
	protected $twitter        = [Twitter::class];
	protected $process        = [Process::class];
	protected $cache          = [Caches::class];
}
