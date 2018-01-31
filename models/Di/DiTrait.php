<?php

namespace Di;

trait DiTrait
{
	function init(\Phalcon\Config $config)
	{
		$this->setShared('config', function() use ($config) {
			return $config;
		});

		$this->setShared('mongo', function() use ($config) {
			static $mongo;
			if (!isset($mongo)) {
				$mongo = new \MongoDB\Client($config->mongo);
			}
			return $mongo;
		});
	}

}
