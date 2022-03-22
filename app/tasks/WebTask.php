<?php

use Service\CmdBufferReader;
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
		$cmd  = new CmdBufferReader("curl -ks https://127.0.0.1/index/$name");
		StdIo::outln($cmd->read());
	}

}
