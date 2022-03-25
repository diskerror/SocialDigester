<?php

use Structure\Config;

return new Config([
	'userConfigName' => '.digester',

	'version' => 'v0.4',

	'configPath' => __DIR__,

	'mongo_db' => [
		'host'     => 'mongodb://localhost:27017',
		'database' => 'digester',
	],

	'twitterOAuth' => [
		'consumer_key'    => 'xxx',
		'consumer_secret' => 'yyy',
		'token'           => 'zzz',
		'token_secret'    => 'www',
	],

	'process' => [
		'name'    => 'digester',
		'path'    => '/var/run/',
		'procDir' => '/proc/',
	],

	'cache' => [
		'index' => [
			'front' => [
				'lifetime' => 600,
				'adapter'  => 'data',
			],
			'back'  => [
				'dir'     => '/dev/shm/digester/',
				'prefix'  => 'index',
				'adapter' => 'file',
			],
		],

		'tag_cloud' => [
			'front' => [
				'lifetime' => 2,
				'adapter'  => 'data',
			],
			'back'  => [
				'dir'     => '/dev/shm/digester/',
				'prefix'  => 'tag_cloud',
				'adapter' => 'file',
			],
		],

		'summary' => [
			'front' => [
				'lifetime' => 6,
				'adapter'  => 'data',
			],
			'back'  => [
				'dir'     => '/dev/shm/digester/',
				'prefix'  => 'summary',
				'adapter' => 'file',
			],
		],
	],

]);
