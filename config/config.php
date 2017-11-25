<?php

$config = new \Phalcon\Config([

	'version' => '0.2',

	/**
	 * CLI: if true, then we print a new line at the end of each execution
	 */
	'printNewLine' => true,

	'mongo' => 'mongodb://localhost:27017',

	'twitter' => [
		'consumer_key'			=> '',
		'consumer_secret'		=> '',
		'oauth_token'			=> '',
		'oauth_token_secret'	=> ''
	],

	'application' => array(
		'modelsDir'		 => APP_PATH . '/models/',
		'viewsDir'		 => APP_PATH . '/views/',
		'baseUri'		 => '/html/',
	)

]);

\Diskerror\Utilities\Registry::set('mongo', 'mongodb://localhost:27017');

// \Diskerror\Utilities\Registry::set('twitter', [
// 	'consumer_key'			=> '',
// 	'consumer_secret'		=> '',
// 	'oauth_token'			=> '',
// 	'oauth_token_secret'	=> ''
// ]);

\Diskerror\Utilities\Registry::set('tracking_data', [
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
]);
