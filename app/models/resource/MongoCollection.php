<?php

namespace Resource;

use Diskerror\TypedBSON\TypedArray;
use LogicException;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;
use Structure\CollectionDef;
use Structure\Config\MongoDB;

/**
 * Class MongoCollection
 *
 * @package Resource
 */
class MongoCollection
{
	/**
	 * @var Database
	 */
	private static Database $_thisDb;

	protected Collection $_collection;

	/**
	 * Name of the collection to use.
	 *
	 * @var string
	 */
	protected string $_collectionName;

	/**
	 * The name of the class in which to convert each returned document.
	 *
	 * @var string
	 */
	protected string $_class;

	/**
	 * An array of index definitions.
	 *
	 * @var array
	 */
	protected array $_indexKeys = [];

	/**
	 * An array of index options to be applied to all indexes.
	 *
	 * @var array
	 */
	protected array $_indexOptions = [];

	/**
	 * MongoCollection constructor.
	 *
	 * @param MongoDB $config
	 * @param CollectionDef $collDefs
	 */
	public function __construct(MongoDB $config, CollectionDef $collDefs)
	{
		if (!isset(self::$_thisDb)) {
			self::$_thisDb = (new Client($config->host))->selectDatabase($config->database);
		}

		$this->_collectionName = $collDefs->name;
		$this->_class          = $collDefs->class;
		$this->_indexKeys      = $collDefs->indexKeys->toArray();

		$this->_collection = self::$_thisDb->selectCollection($this->_collectionName);
	}


	/**
	 * Apply collection index definition.
	 */
	public function doIndex(): void
	{
		$this->_collection->insertOne([]);    //	Creates collection if it doesn't exist.
		$this->_collection->dropIndexes();    //	Start clean if collection existed.
		$this->_collection->deleteMany([]);
		$this->_collection->createIndexes($this->_indexKeys);
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
