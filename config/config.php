<?php

/**
 * All nested arrays are also converted to Phalcon\Config objects.
 *
 * To add to or override these values create another file that ends in '.php' with contents like:
 *
 * $config->offsetSet(
 *     'twitter_auth',
 *     [
 *         'consumer_key'          => 'wwww',
 *         'consumer_secret'       => 'xxxx',
 *         'oauth_token'           => 'yyyy',
 *         'oauth_token_secret'    => 'zzzz'
 *     ]
 * );
 *
 */

$config = new \Phalcon\Config([

	'version' => '0.2',

	/**
	 * CLI: if true, then we print a new line at the end of each execution
	 */
	'printNewLine' => true,

	'mongo' => 'mongodb://localhost:27017',

	'twitter_auth' => [
		'consumer_key'			=> '',
		'consumer_secret'		=> '',
		'oauth_token'			=> '',
		'oauth_token_secret'	=> ''
	],

	'tracking_data' => [
		'chuckschumer',
		'democrat',
		'donald',
		'donaldtrump',
		'kevinmccarthy',
		'mccarthy',
		'mcconnell',
		'mikepence',
		'mitch',
		'mitchmcconnell',
		'nancypelosi',
		'paulryan',
		'pelosi',
		'pence',
		'potus',
		'republican',
		'schumer',
		'scotus',
		'trump'
	],

	'application' => array(
		'modelsDir'		 => APP_PATH . '/models/',
		'viewsDir'		 => APP_PATH . '/views/',
		'baseUri'		 => '/html/',
	)

]);
