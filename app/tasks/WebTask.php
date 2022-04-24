<?php

use Service\Curl;
use Service\StdIo;

/**
 * Class WebTask
 *
 * Great for debugging the core calls used by a web controller class.
 *
 */
class WebTask extends TaskMaster
{
	public function __call(string $name, array $args): void
	{
		$name = substr($name, 0, -6);
		$curl = new Curl("https://127.0.0.1/index/$name");
		$curl->setopt_array([
			CURLOPT_RETURNTRANSFER   => true,
			CURLOPT_HEADER           => false,
			CURLOPT_USERAGENT        => $this->config->process->name,
			CURLOPT_AUTOREFERER      => true,
			CURLOPT_SSL_VERIFYPEER   => false,
			CURLOPT_SSL_VERIFYHOST   => false,
			CURLOPT_SSL_VERIFYSTATUS => false,
			CURLOPT_TIMEOUT          => 120,
		]);
		StdIo::outln($curl->exec());
	}

}
