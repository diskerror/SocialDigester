<?php

use Structure\CollectionDef;
use Structure\Tweet;

return new CollectionDef([
	'name'      => 'tweets',
	'class'     => Tweet::class,
	'indexKeys' => [
		['key' => ['created_at' => 1], 'expireAfterSeconds' => 600],
		['key' => ['entities . hashtags.0 . text' => 1]],
	],
]);
