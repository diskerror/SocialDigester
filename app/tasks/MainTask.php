<?php

use Service\StdIo;

class MainTask extends TaskMaster
{
	/**
	 * Display list of commands, subcommands, and config structure.
	 */
	public function mainAction()
	{
		StdIo::outln('Usage: ./run [command [sub-command [arguments...]]]');
		StdIo::outln();

		foreach (glob(__DIR__ . '/*Task.php') as $fileName) {
			$className = basename($fileName, '.php');

			if ($className === 'MainTask') {
				continue;
			}

			$cmd = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', substr($className, 0, -4)));
			StdIo::outln("Command:\n\t" . $cmd . "\n");

			StdIo::outln('Sub-commands:');
			$refl = new Service\Reflector($className);
			foreach ($refl->getFormattedDescriptions() as $description) {
				StdIo::outln("\t" . $description);
			}
			StdIo::outln();
		}

		StdIo::outln('A hidden file named "' . $this->config['user_config_name'] . '" must be in the users home directory');
		StdIo::outln('and containing configuration data in this form:');

		$demoConfig = new Structure\Config();

		$demoConfig->syteline_dbs[]->configName = 'us';
		$demoConfig->syteline_dbs[]->configName = 'can';

		$demoConfig = $demoConfig->toArray();
		unset($demoConfig['mongo_db']['collections']);  //  comes from application.config.php

		StdIo::outln('<?php');
		StdIo::out('return ');
		StdIo::phpOut($demoConfig);
	}
}
