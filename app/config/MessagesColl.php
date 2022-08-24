<?php

use Structure\CollectionDef;

return new CollectionDef([
	'name'      => 'messages',
	'class'     => '',
	'indexKeys' => [
		['key' => ['created' => 1], 'expireAfterSeconds' => 3600 * 24],
	],
]);
