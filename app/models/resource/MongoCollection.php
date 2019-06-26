<?php

namespace Resource;

use Diskerror\TypedBSON\TypedArray;
use InvalidArgumentException;
use MongoDB\Collection;
use Phalcon\Di;

/**
 * Class MongoCollection
 * @package Resource
 */
abstract class MongoCollection
{
	/**
	 * Name of the collection to use.
	 * @var string
	 */
	protected $_collection;

	/**
	 * The name of the class in which to convert each returned document.
	 * @var string
	 */
	protected $_class;

	private $_client;

	abstract public function doIndex(int $expire=0);

	/**
	 * @return Collection
	 */
	public function getClient(): Collection
	{
		if (!isset($this->_client)) {
			$this->_client = Di::getDefault()->getShared('mongo')->{$this->_collection};
		}
		return $this->_client;
	}

	public function __call($name, $args)
	{
		switch (count($args)) {
			case 0:
				return $this->getClient()->{$name}();

			case 1:
				return $this->getClient()->{$name}($args[0]);

			case 2:
				return $this->getClient()->{$name}($args[0], $args[1]);

			default:
				throw new InvalidArgumentException('too many arguments');
		}
	}

	public function find($filter = [], array $options = [])
	{
		$res = $this->getClient()->find($filter, $options);
		return new TypedArray($this->_class, $res);
	}

	public function findOne($filter = [], array $options = [])
	{
		$res = $this->getClient()->findOne($filter, $options);
		return new $this->_class($res);
	}
}
