<?php

namespace Structure\Config;


use Diskerror\Typed\TypedClass;

/**
 * @property Cache $index
 * @property Cache $tag_cloud
 * @property Cache $summary
 */
class Caches extends TypedClass
{
	protected $index     = [Cache::class];
	protected $tag_cloud = [Cache::class];
	protected $summary   = [Cache::class];
}
