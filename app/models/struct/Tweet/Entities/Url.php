<?php

namespace Structure\Tweet\Entities;

use Diskerror\TypedBSON\TypedArray;
use Diskerror\TypedBSON\TypedClass;

/**
 * @property $url
 * @property $expanded_url
 * @property $display_url
 * @property array $indices
 */
class Url extends TypedClass
{
	protected $url          = '';

	protected $expanded_url = '';

	protected $display_url  = '';

	protected $indices      = [TypedArray::class, 'int'];
}
