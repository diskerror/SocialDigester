<?php

/**
 * All nested arrays are converted to nested Phalcon\Config objects.
 *
 * To add to or override these values
 * create another file in this directory
 * that ends in '.php' with contents like:
 *
 * $config->twitter->offsetSet(
 *     'auth',
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

	'version' => '0.3',

	/**
	 * CLI: if true, then we print a new line at the end of each execution
	 */
	'printNewLine' => true,

	'mongo' => 'mongodb://localhost:27017',

	//	Save tweets for 30 minutes.
	'mongo_expire' => 1800,

	'word_stats' => [
		'count'		=> 120,
		'window'	=> 300, // seconds
		'stop'		=> [],
	],

	'twitter' => [
		'auth' => [
			'consumer_key'			=> '',
			'consumer_secret'		=> '',
			'oauth_token'			=> '',
			'oauth_token_secret'	=> ''
		],

		'track' => [
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

	],

	'process' => [
		'name' => 'tweets',
		'path' => '/var/run/twitter_digester',
		'procDir' => '/proc/'		//	location of actual PID
	],

	'application' => [
		'modelsDir'		 => APP_PATH . '/models/',
		'viewsDir'		 => APP_PATH . '/views/',
		'baseUri'		 => '/html/',
	]

]);
