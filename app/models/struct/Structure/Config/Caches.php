<?php

namespace Structure\Config;


use Diskerror\Typed\TypedClass;

class Caches extends TypedClass
{
	protected $index     = [Cache::class];
	protected $tag_cloud = [Cache::class];
	protected $summary   = [Cache::class];
}
