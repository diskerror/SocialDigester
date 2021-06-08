<?php

namespace Structure\Tweet;

use Diskerror\TypedBSON\TypedClass;

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
