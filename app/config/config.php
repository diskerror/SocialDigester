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

return [

	'version' => '0.4',

	'mongo_db' => [
		'host'     => 'mongodb://localhost:27017',
		'database' => 'digester',
	],

	'tweets_expire' => 600,   //	In seconds. Save tweets for 10 minutes.

	'word_stats' => [
		'quantity' => 32,     //	return the top X items
		'window'   => 180,    //	summarize the last X seconds
		'stop'     => [],     //	stop words
	],

	'twitter' => [
		'auth' => [
			'consumer_key'       => '',
			'consumer_secret'    => '',
			'oauth_token'        => '',
			'oauth_token_secret' => '',
		],

		'track' => [
			'atf',
			'attorney',
			'cia',
			'congress',
			'constitution',
			'constitutionparty',
			'court',
			'democracy',
			'democrat',
			'democratic',
			'democraticparty',
			'democratparty',
			'diplomacy',
			'diplomatic',
			'doj',
			'fbi',
			'gop',
			'government',
			'green',
			'greenparty',
			'ice',
			'justice',
			'libertarian',
			'libertarianparty',
			'potus',
			'presidency',
			'president',
			'representative',
			'republic',
			'republican',
			'republicanparty',
			'scotus',
			'senate',
			'senator',
			'socialdemocrat',
			'socialdemocraticparty',
			'supreme',
			'supremecourt',
		],
	],

	'process' => [
		'name'    => 'twitter_digester',
		'path'    => '/var/run/twitter_digester',
		'procDir' => '/proc/'    //	location of actual PID
	],

	'index_cache' => [
		'front' => [
			'lifetime' => 600,    //	ten minutes
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

	'summary_cache' => [
		'front' => [
			'lifetime' => 6,    //	six seconds
			'adapter'  => 'data',
		],
		'back'  => [
			'cacheDir' => '/dev/shm/twitter_digester/',
			'prefix'   => 'summary',
			'frontend' => null,
			'adapter'  => 'file',
		],
	],

];
