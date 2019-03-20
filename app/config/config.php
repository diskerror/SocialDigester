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
	'wordStats' => [
		//	return the top X items
		'quantity' => 100,
		//	summarize the last X seconds
		'window'   => 300,
		//	stop words
		'stop'     => [
			'about',
			'after',
			'all',
			'and',
			'are',
			'back',
			'been',
			'before',
			'being',
			'but',
			'can',
			'could',
			'did',
			'even',
			'field',
			'for',
			'from',
			'get',
			'going',
			'had',
			'has',
			'have',
			'her',
			'him',
			'his',
			'how',
			'http',
			'https',
			'into',
			'it\'s',
			'its',
			'just',
			'let',
			'make',
			'man',
			'many',
			'more',
			'most',
			'much',
			'not',
			'now',
			'only',
			'other',
			'over',
			'really',
			'see',
			'she',
			'since',
			'still',
			'than',
			'that',
			'the',
			'their',
			'them',
			'they',
			'this',
			'those',
			'very',
			'via',
			'was',
			'what',
			'when',
			'who',
			'will',
			'with',
			'would',
			'you',
			'your',
		],
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
