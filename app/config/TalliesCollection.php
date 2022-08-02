<?php

use Structure\CollectionDefinition;
use Structure\Tally;

return new CollectionDefinition([
	'name'      => 'tallies',
	'class'     => Tally::class,
	'indexKeys' => [
		['key' => ['created' => 1], 'expireAfterSeconds' => 3600],
	],
]);
