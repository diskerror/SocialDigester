<?php

use Structure\CollectionDef;
use Structure\Snapshot;

return new CollectionDef([
	'name'      => 'snapshots',
	'class'     => Snapshot::class,
	'indexKeys' => [
		['key' => ['created' => -1]],
	],
]);
