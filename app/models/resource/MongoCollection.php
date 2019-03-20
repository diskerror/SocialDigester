<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 7/17/18
 * Time: 5:44 PM
 */

namespace Resource;

use Diskerror\Typed\TypedArray;

/**
 * Class MongoCollection
 *
 * @package Resource
 */
abstract class MongoCollection
{
	/**
	 * Name of the collection to use.
	 *
	 * @var string
	 */
	protected $_collection;

	private   $_client;

	/**
	 * MongoCollection constructor.
	 */
	public function __construct() { }

	abstract public function doIndex(int $expire = 0);

	public function insertOne($document, array $options = [])
	{
		return self::getClient()->insertOne($document, $options);
	}

	/**
	 * @return \MongoDB\Collection
	 */
	public function getClient()
	{
		if (!isset($this->_client)) {
			$this->_client = \Phalcon\Di::getDefault()->getShared('mongodb')->{$this->_collection};
		}
		return $this->_client;
	}

	public function insertMany($document, array $options = [])
	{
		return self::getClient()->insertMany($document, $options);
	}

	public function __call($name, $args)
	{
		switch (count($args)) {
			case 0:
				return self::getClient()->{$name}();

			case 1:
				return self::getClient()->{$name}($args[0]);

			case 2:
				return self::getClient()->{$name}($args[0], $args[1]);

			default:
				throw new \InvalidArgumentException('too many arguments');
		}
	}

	public function find($filter = [], array $options = [])
	{
		return self::getClient()->find($filter, $options);
	}

	public function findOne($filter = [], array $options = [])
	{
		return self::getClient()->findOne($filter, $options);
	}
}
