<?php

use Structure\Config;

return new Config([
	'userConfigName' => '.digester',

	'configPath' => __DIR__,

	'twitter' => [
		'auth' => [
			'consumer_key'    => 'xxx',
			'consumer_secret' => 'yyy',
			'token'           => 'zzz',
			'token_secret'    => 'www',
		],
	],

	'process' => [
		'name' => 'digester',
	],

	'cache' => [
		'index' => [
			'front' => [
				'lifetime' => 600,    //	ten minutes
			],
			'back'  => [
				'dir'    => '/dev/shm/digester/',
				'prefix' => 'index',
			],
		],

		'tag_cloud' => [
			'front' => [
				'lifetime' => 2,    //	two seconds
			],
			'back'  => [
				'dir'    => '/dev/shm/digester/',
				'prefix' => 'tag_cloud',
			],
		],

		'summary' => [
			'front' => [
				'lifetime' => 6,    //	six seconds
			],
			'back'  => [
				'dir'    => '/dev/shm/digester/',
				'prefix' => 'summary',
			],
		],
	],
]);
