<?php

/**
 * All nested arrays are converted to nested Phalcon\Config objects.
 *
 * To add to or override these values
 * create another file in this directory
 * that ends in '.php' with contents like:
 *
 * return [
 *        'twitter'    => [
 *            'auth' => [
 *                'consumer_key'       => 'wwww',
 *                'consumer_secret'    => 'xxxx',
 *                'token'        => 'yyyy',
 *                'token_secret' => 'zzzz',
 *            ],
 *        ],
 *    ];
 */

return [

	//	Name of the user's configuration file.
	'userConfigName' => '.politicator.php',

	'version' => '0.4',

	'mongo_db' => [
		'host'     => 'mongodb://localhost:27017',
		'database' => 'digester',
	],

	'twitter' => [
		'url' => 'https://stream.twitter.com/1.1/',

		'auth' => [
			'consumer_key'    => '',
			'consumer_secret' => '',
			'token'           => '',
			'token_secret'    => '',
		],
	],

	'process' => [
		'name'    => 'twitter_digester',
		'path'    => '/var/run/',
		'procDir' => '/proc/'    //	location of actual PID
	],

	'cache' => [
		'index' => [
			'front' => [
				'lifetime' => 600,    //	ten minutes
				'adapter'  => 'data',
			],
			'back'  => [
				'dir'      => '/run/shm/twitter_digester/',
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
				'dir'      => '/run/shm/twitter_digester/',
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
				'dir'      => '/run/shm/twitter_digester/',
				'prefix'   => 'summary',
				'frontend' => null,
				'adapter'  => 'file',
			],
		],

	],

];
