<?php

namespace Di;

class Cli extends \Phalcon\Di\FactoryDefault\Cli
{
	use DiTrait;

	function __construct(\Phalcon\Config $config)
	{
		parent::__construct();
		$this->init($config);
	}

}
