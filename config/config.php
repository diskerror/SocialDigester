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
 *         'oauth_token_secret'    => 'zzzz',
 *     ]
 * );
 *
 */

$config = new \Phalcon\Config([

	'version'      => '0.3',

	/**
	 * CLI: if true, then we print a new line at the end of each execution
	 */
	'printNewLine' => true,

	'mongo' => [
		'host'       => 'mongodb://localhost:27017',
		'database'   => 'digester',
		'collection' => 'tweets',
		'expire'     => 1200,        //	In seconds. Save tweets for 20 minutes.
	],

	'word_stats' => [
		'count'  => 100,    //	return the top X items
		'window' => 300,    //	summarize the last X seconds
		'stop'   => [],        //	stop words
	],

	'twitter' => [
		'auth' => [
			'consumer_key'       => '',
			'consumer_secret'    => '',
			'oauth_token'        => '',
			'oauth_token_secret' => '',
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
			'trump',
		],
	],

	'process' => [
		'name'    => 'tweets',
		'path'    => '/var/run/twitter_digester',
		'procDir' => '/proc/'    //	location of actual PID
	],

	'index_cache' => [
		'front' => [
			'lifetime' => 300,    //	five minutes
			'adapter'  => 'data',
		],
		'back'  => [
			'cacheDir' => '/dev/shm/twitter_digester/',
			'prefix'   => 'index',
			'frontend' => null,
			'adapter'  => 'file',
		],
	],

	'tag_cloud_cache' => [
		'front' => [
			'lifetime' => 2,    //	two seconds
			'adapter'  => 'data',
		],
		'back'  => [
			'cacheDir' => '/dev/shm/twitter_digester/',
			'prefix'   => 'tag_cloud',
			'frontend' => null,
			'adapter'  => 'file',
		],
	],

]);
