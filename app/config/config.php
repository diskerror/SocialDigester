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

	'mongodb' => [
		'host'        => 'mongodb://localhost:27017',
		'database'    => 'politicator',

		//  The list of active collections. Listing the names  here prevents typos from
		//      creating new and mysterious collections.
		//  The keys are the collection names and the values are a list of the  index definitions.
		'collections' => [
			'tweet'    => [
				['keys' => ['created_at' => 1], 'options' => ['expireAfterSeconds' => 60 * 20]],
				['keys' => ['entities.hashtags.0.text' => 1]],
				['keys' => ['text' => 1]],
			],
			'message'  => [
				['keys' => ['created' => 1], 'options' => ['expireAfterSeconds' => 60 * 60]],
			],
			'snapshot' => [
				//	_id is automatically indexed
			],

		],
	],

	'wordStats' => [
		'quantity' => 100,    //	return the top X items
		'window'   => 300,    //	summarize the last X seconds
		'stop'     => [],     //	stop words
	],

	'twitter' => [
		'url' => 'https://stream.twitter.com/1.1/',

		'auth' => [
			'consumer_key'       => '',
			'consumer_secret'    => '',
			'oauth_token'        => '',
			'oauth_token_secret' => '',
		],

		'track' => [
			'chuckschumer',
			'constitution',
			'democrat',
			'donald',
			'donaldtrump',
			'green',
			'kevinmccarthy',
			'libertarian',
			'mccarthy',
			'mcconnell',
			'mikepence',
			'mitch',
			'mitchmcconnell',
			'nancypelosi',
			'pelosi',
			'pence',
			'potus',
			'republican',
			'schumer',
			'scotus',
			'socialdemocrat',
			'trump',
		],
	],

	'process' => [
		'name'    => 'politicator',
		'path'    => '/var/run/politicator',
		'procDir' => '/proc/'    //	location of actual PID
	],

	'caches' => [
		'index' => [
			'front' => [
				'lifetime' => 600,    //	ten minutes
				'adapter'  => 'data',
			],
			'back'  => [
				'cacheDir' => '/dev/shm/politicator/',
				'prefix'   => 'index',
				'frontend' => null,
				'adapter'  => 'file',
			],
		],

		'tag_cloud' => [
			'front' => [
				'lifetime' => 2,    //	two seconds
				'adapter'  => 'data',
			],
			'back'  => [
				'cacheDir' => '/dev/shm/politicator/',
				'prefix'   => 'tag_cloud',
				'frontend' => null,
				'adapter'  => 'file',
			],
		],

		'summary' => [
			'front' => [
				'lifetime' => 6,    //	six seconds
				'adapter'  => 'data',
			],
			'back'  => [
				'cacheDir' => '/dev/shm/politicator/',
				'prefix'   => 'summary',
				'frontend' => null,
				'adapter'  => 'file',
			],
		],
	],

];
