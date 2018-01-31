<?php

namespace Di;

class Web extends \Phalcon\Di\FactoryDefault
{
	use DiTrait;

	function __construct(\Phalcon\Config $config)
	{
		parent::__construct();
		$this->init($config);
	}

}
