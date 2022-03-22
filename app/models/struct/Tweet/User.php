<?php

namespace Structure\Tweet;

use Diskerror\Typed\Scalar\TStringNormalize;
use Diskerror\TypedBSON\TypedClass;

/**
 * @property string $id
 * @property string $name
 * @property string $screen_name
 * @property string $location
 * @property bool $contributors_enabled
 * @property Created $created_at
 * @property string $description
 * @property int $favorites_count
 * @property int $followers_count
 * @property int $friends_count
 * @property bool $geo_enabled
 * @property bool $is_translator
 * @property string $lang
 * @property int $listed_count
 * @property bool $protected
 * @property int $statuses_count
 * @property string $time_zone
 * @property string $url
 * @property bool $verified
 *
 */
class User extends TypedClass
{
	protected $id = '';

//	protected $name                 = [TStringNormalize::class];

	protected $screen_name = [TStringNormalize::class];

	protected $location = '';

//	protected $contributors_enabled = false;
//
//	protected $created_at           = [Created::class];
//
//	protected $description          = [TStringNormalize::class];
//
//	protected $favorites_count     = 0;
//
//	protected $followers_count      = 0;
//
//	protected $friends_count        = 0;
//
//	protected $geo_enabled          = false;
//
//	protected $is_translator        = false;
//
//	protected $lang                 = 'en';
//
//	protected $listed_count         = 0;
//
//	protected $protected            = false;
//
//	protected $statuses_count       = 0;
//
//	protected $time_zone            = '';
//
//	protected $url                  = '';

	protected $verified = false;

}
