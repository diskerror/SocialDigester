<?php

/**
 * Always open this configuration file with it's default values.
 */
$configFile = APP_PATH . '/config/config.php';
require $configFile;

/**
 * Open all other files ending with '.php' as a configuration file.
 */
foreach (glob(APP_PATH . '/config/*.php') as $g) {
	if ($g !== $configFile) {
		require $g;
	}
}
