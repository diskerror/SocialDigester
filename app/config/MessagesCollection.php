<?php

use Structure\CollectionDefinition;

return new CollectionDefinition([
	'name'      => 'messages',
	'class'     => '',
	'indexKeys' => [
		['key' => ['created' => 1], 'expireAfterSeconds' => 3600 * 24],
	],
]);
