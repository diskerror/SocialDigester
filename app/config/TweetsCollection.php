<?php

use Structure\CollectionDefinition;
use Structure\Tweet;

return new CollectionDefinition([
	'name'      => 'tweets',
	'class'     => Tweet::class,
	'indexKeys' => [
		['key' => ['created_at' => 1], 'expireAfterSeconds' => 3600],
		['key' => ['entities . hashtags.0 . text' => 1]],
	],
]);
