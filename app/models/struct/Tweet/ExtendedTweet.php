<?php


namespace Structure\Tweet;


use Diskerror\Typed\Scalar\TIntegerUnsigned;
use Diskerror\Typed\Scalar\TStringNormalize;
use Diskerror\Typed\TypedClass;
use Diskerror\TypedBSON\TypedArray;

/**
 * Class ExtendedTweet
 *
 * @package Structure\Tweet
 *
 * @property $full_text string
 * @property $display_text_range array
 * @property $entities Entities
 */
class ExtendedTweet extends TypedClass
{
	/**
	 * @var string
	 */
	protected $full_text = [TStringNormalize::class];

	/**
	 * @var array
	 */
//	protected $display_text_range = [TypedArray::class, TIntegerUnsigned::class];

	/**
	 * @var Entities
	 */
	protected $entities = [Entities::class];
}
