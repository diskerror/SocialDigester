<?php

namespace Resource;

use Diskerror\TypedBSON\TypedArray;
use InvalidArgumentException;
use LogicException;
use MongoDB\Collection;
use Phalcon\Di;

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

	/**
	 * The name of the class in which to convert each returned document.
	 *
	 * @var string
	 */
	protected $_class;


	private $_client;

	/**
	 * MongoCollection constructor.
	 *
	 * @param string $_class
	 */
	public function __construct()
	{
		if (!isset($this->_collection) || !isset($this->_class)) {
			throw new LogicException('Both the collection name and class name must be defined in child class.');
		}

		$this->_client = Di::getDefault()->getShared('mongo')->{$this->_collection};
	}


	abstract public function doIndex(int $expire = 0);

	/**
	 * @return Collection
	 */
	public function getClient(): Collection
	{
		return $this->_client;
	}

	public function __call($name, $args)
	{
		switch (count($args)) {
			case 0:
				return $this->_client->{$name}();

			case 1:
				return $this->_client->{$name}($args[0]);

			case 2:
				return $this->_client->{$name}($args[0], $args[1]);

			default:
				throw new InvalidArgumentException('too many arguments');
		}
	}

	public function find($filter = [], array $options = [])
	{
		$res = $this->_client->find($filter, $options);
		return new TypedArray($this->_class, $res);
	}

	public function findOne($filter = [], array $options = [])
	{
		$res = $this->_client->findOne($filter, $options);
		return new $this->_class($res);
	}
}
