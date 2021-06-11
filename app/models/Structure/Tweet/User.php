<?php

namespace Structure\Tweet;

use Diskerror\Typed\Scalar\TStringNormalize;
use Diskerror\TypedBSON\DateTime;
use Diskerror\TypedBSON\TypedClass;

class User extends TypedClass
{
	protected $id                   = '';

	protected $name                 = '';

	protected $screen_name          = '';

	protected $location             = '';

	protected $contributors_enabled = false;

	protected $created_at           = [DateTime::class];

	protected $description          = [TStringNormalize::class];

	protected $favourites_count     = 0;

	protected $followers_count      = 0;

	protected $friends_count        = 0;

	protected $geo_enabled          = false;

	protected $is_translator        = false;

	protected $lang                 = 'en';

	protected $listed_count         = 0;

	protected $protected            = false;

	protected $statuses_count       = 0;

	protected $time_zone            = '';

	protected $url                  = '';

	protected $verified             = false;

}
