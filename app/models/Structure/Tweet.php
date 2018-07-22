<?php

namespace Structure;

use Diskerror\Typed\ArrayOptions as AO;

class Tweet extends \Diskerror\Typed\TypedClass
{
	protected $_map = [
		'id'  => 'id_',    //	from Twitter
		'_id' => 'id_',    //	from Mongo
	];


	protected $_nullCreatesNullInstance = true;

	/**
	 * Only this top level "id" is used for the MongoDb "_id" auto index.
	 * The "toArray" method with SWITCH_ID changes "id_" to "_id".
	 */
	protected $id_ = 0;

	use Tweet\TweetTrait;

//	protected $retweeted_status = '__class__\Structure\Tweet\Retweet';


	public function __construct($in = null)
	{
		parent::__construct($in);
		$this->setArrayOptions(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::KEEP_JSON_EXPR);
	}
}
