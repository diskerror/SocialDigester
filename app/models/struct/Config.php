<?php

namespace Structure;

use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;
use Structure\Config\Cache;
use Structure\Config\Mongo;
use Structure\Config\Process;
use Structure\Config\Twitter;
use Structure\Config\WordStats;


/**
 * Class Config
 *
 * @package Structure
 *
 * @property $version
 * @property $userConfigName
 * @property $mongodb
 * @property $wordStats
 * @property $track
 * @property $twitter
 * @property $process
 * @property $caches
 */
class Config extends TypedClass
{
	protected $version        = '0.6';
	protected $userConfigName = '';
	protected $mongodb        = [Mongo::class];
	protected $wordStats      = [WordStats::class];
	protected $track          = [TypedArray::class, TString::class];
	protected $twitter        = [Twitter::class];
	protected $process        = [Process::class];
	protected $caches         = [TypedArray::class, Cache::class];
}
