<?php

namespace Service\Application;


use Phalcon\Cli\Console;
use Phalcon\Cli\Dispatcher\Exception;
use Phalcon\Di\FactoryDefault\Cli as FdCli;
use Service\StdIo;

class Cli extends DiAbstract
{
	public function initDi(): self
	{
		$di = new FdCli();

		parent::_commonDi($di);

		$this->_application = new Console($di);

		return $this;
	}

	/**
	 * @param array $argv
	 */
	public function run(array $argv = []): void
	{
		try {
			// Parse CLI arguments.
			//	CLI options will be parsed into $config later.
			$args = [];
			if (array_key_exists(1, $argv)) {
				$args['task'] = $argv[1];

				if (array_key_exists(2, $argv)) {
					$args['action'] = $argv[2];

					if (array_key_exists(3, $argv)) {
						$args['params'][] = $argv[3];
					}
				}
			}

			$this->_application->handle($args);
		}
		catch (Exception $e) {
			StdIo::err($e->getMessage());
		}
	}
}
