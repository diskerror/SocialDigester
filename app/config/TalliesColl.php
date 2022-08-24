<?php

use Structure\CollectionDef;
use Structure\Tally;

return new CollectionDef([
	'name'      => 'tallies',
	'class'     => Tally::class,
	'indexKeys' => [
		['key' => ['created' => 1], 'expireAfterSeconds' => 600],
	],
]);
