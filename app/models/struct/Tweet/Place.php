<?php

namespace Structure\Tweet;

use Diskerror\TypedBSON\TypedClass;

/**
 * @property $id
 * @property $url
 * @property $place_type
 * @property $name
 * @property $full_name
 * @property $country_code
 * @property $country
 */
class Place extends TypedClass
{
	protected $id           = '';

	protected $url          = '';

	protected $place_type   = '';

	protected $name         = '';

	protected $full_name    = '';

	protected $country_code = '';

	protected $country      = '';
}
