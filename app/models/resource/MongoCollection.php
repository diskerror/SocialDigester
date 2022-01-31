<?php

namespace Resource;

use Diskerror\TypedBSON\TypedArray;
use LogicException;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;
use Service\StdIo;
use Structure\Config\Mongo;
use function key_exists;

/**
 * Class MongoCollection
 *
 * @package Resource
 */
abstract class MongoCollection
{
	/**
	 * @var Database
	 */
	private static $_thisDb;

	protected $_collection;

	/**
	 * Name of the collection to use.
	 *
	 * @var string
	 */
	protected $_collectionName;

	/**
	 * The name of the class in which to convert each returned document.
	 *
	 * @var string
	 */
	protected $_class;

	/**
	 * An array of index definitions.
	 *
	 * @var array
	 */
	protected $_indexKeys = [];

	/**
	 * An array of index options to be applied to all indexes.
	 *
	 * @var array
	 */
	protected $_indexOptions = [];

	/**
	 * MongoCollection constructor.
	 *
	 * @param Mongo $config
	 */
	public function __construct(Mongo $config)
	{
		if (!isset($this->_collectionName) || !isset($this->_class)) {
			throw new LogicException('Both the collection name and class name must be defined in child class.');
		}

		if (!isset(self::$_thisDb)) {
			self::$_thisDb = (new Client($config->host))->selectDatabase($config->database);
		}

		$this->_collection = self::$_thisDb->selectCollection($this->_collectionName);
	}


	/**
	 * Apply collection index definition.
	 */
	public function doIndex(): void
	{
//		foreach ($this->_indexes as $index) {
//			StdIo::outln($this->_collectionName);
//			StdIo::jsonOut($index);
//			$this->_collection->insertOne([]);    //	Creates collection if it doesn't exist.
//			$this->_collection->dropIndexes();    //	Start clean if collection existed.
//			if (key_exists('options', $index)) {
//				$this->_collection->createIndex($index['key'], $index['options']);
//			}
//			else {
//				$this->_collection->createIndex($index['key']);
//			}
//		}

		$this->_collection->insertOne([]);    //	Creates collection if it doesn't exist.
		$this->_collection->dropIndexes();    //	Start clean if collection existed.
		$this->_collection->deleteMany([]);
		$this->_collection->createIndexes($this->_indexKeys);
	}

	/**
	 * @return Collection
	 */
	public function getCollection(): Collection
	{
		return $this->_collection;
	}

	public function __call($name, $args)
	{
		return $this->_collection->{$name}(...$args);
	}

	public function find($filter = [], array $options = []): TypedArray
	{
		return new TypedArray($this->_class, $this->_collection->find($filter, $options));
	}

	public function findOne($filter = [], array $options = [])
	{
		return new $this->_class($this->_collection->findOne($filter, $options));
	}
}
