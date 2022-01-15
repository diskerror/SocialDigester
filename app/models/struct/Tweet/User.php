<?php

namespace Structure\Tweet;

use Diskerror\Typed\Scalar\TStringNormalize;
use Diskerror\TypedBSON\TypedClass;

/**
 * @property $id
 * @property $name
 * @property $screen_name
 * @property $location
 * @property $contributors_enabled
 * @property Created $created_at
 * @property string $description
 * @property int $favoriates_count
 * @property $followers_count
 * @property $friends_count
 * @property $geo_enabled
 * @property $is_translator
 * @property $lang
 * @property $listed_count
 * @property $protected
 * @property $statuses_count
 * @property $time_zone
 * @property $url
 * @property $verified
 *
 */
class User extends TypedClass
{
	protected $id                   = '';

	protected $name                 = '';

	protected $screen_name          = '';

	protected $location             = '';

	protected $contributors_enabled = false;

	protected $created_at           = [Created::class];

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
