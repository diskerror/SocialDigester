<?php

use Structure\CollectionDefinition;
use Structure\Snapshot;

return new CollectionDefinition([
	'name'      => 'snapshots',
	'class'     => Snapshot::class,
	'indexKeys' => [
		['key' => ['created' => -1]],
	],
]);
