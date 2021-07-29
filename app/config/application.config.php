<?php
/**
 * If you need an environment-specific system or application configuration,
 * there is an example in the documentation
 *
 * @see https://docs.zendframework.com/tutorials/advanced-config/#environment-specific-system-configuration
 * @see https://docs.zendframework.com/tutorials/advanced-config/#environment-specific-application-configuration
 */
return [
	'appName'        => 'Twitter_Digester',

	//	Name of the user's configuration file.
	'userConfigName' => '.digester.php',

	// Whether or not to enable a configuration cache.
	// If enabled, the merged configuration will be cached and used in
	// subsequent requests.
	//	'configCacheEnabled' => true,

	// Whether or not to enable a module class map cache.
	// If enabled, creates a module class map cache which will be used
	// by in future requests, to reduce the autoloading process.
	//    'moduleMapCacheEnabled' => false,

	// The key used to create the class map cache file name.
	//    'moduleMapCacheKey' => 'application.module.cache',

	// The path in which to cache merged configuration.
	//    'cacheDir' => 'data/cache/',

	// Initial configuration with which to seed the ServiceManager.
	// Should be compatible with Laminas\ServiceManager\Config.
	// 'service_manager' => [],

	'mongodb' => [
		'host'        => 'mongodb://localhost:27017',
		'database'    => 'digester',

		/**
		 * The list of active collections. Listing the names here prevents typos in modified code from
		 *   creating new and mysterious collections.
		 * The keys are the collection names and the values are a list of the index definitions.
		 * The collections "tweets" and "snapshots" structure are defined by the structure
		 *   file "Tweet.php" and "Shapshot.php".
		 */
		'collections' => [
			'tweets'    => [
				['keys' => ['created_at' => 1], 'options' => ['expireAfterSeconds' => 300]],
				['keys' => ['entities.hashtags.0.text' => 1]],
				['keys' => ['text' => 1]],
			],
			'tallies'   => [
				['keys' => ['created' => 1], 'options' => ['expireAfterSeconds' => 300]],
			],
			'messages'  => [
				['keys' => ['created' => 1], 'options' => ['expireAfterSeconds' => 86400]],
			],
			'snapshots' => [
				//	_id is automatically indexed
			],
		],
	],

	'wordStats' => [
		'quantity'  => 100,    //	return the top X items
		'window'    => 300,    //	summarize the last X seconds
		'stopWords' => [
		],
	],

	'track' => [
	],

	'twitter' => [
		'url' => 'https://stream.twitter.com/1.1/statuses/',

		'auth' => [
			'consumerKey'      => 'wwww',
			'consumerSecret'   => 'xxxx',
			'oauthToken'       => 'yyyy',
			'oauthTokenSecret' => 'zzzz',
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
			],
			'back'  => [
				'prefix'   => 'index',
				'frontend' => null,
				'adapter'  => 'file',
			],
		],

		'tag_cloud' => [
			'front' => [
				'lifetime' => 2,    //	two seconds
			],
			'back'  => [
				'prefix'   => 'tag_cloud',
				'frontend' => null,
				'adapter'  => 'file',
			],
		],

		'summary' => [
			'front' => [
				'lifetime' => 6,    //	six seconds
			],
			'back'  => [
				'prefix'   => 'summary',
				'frontend' => null,
				'adapter'  => 'file',
			],
		],
	],
];
