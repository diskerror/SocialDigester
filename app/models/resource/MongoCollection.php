<?php

namespace Resource;

use Diskerror\TypedBSON\TypedArray;
use Exception;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;
use Structure\Config;

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
	protected array $_indexKeys;

	/**
	 * MongoCollection constructor.
	 *
	 * @param Config $config
	 * @param string $collection
	 */
	public function __construct(Config $config, string $collection)
	{
		if (!isset(self::$_thisDb)) {
			self::$_thisDb = (new Client($config->mongo_db->host))->selectDatabase($config->mongo_db->database);
		}

		$collDefs = require $config->configPath . '/' . ucwords(strtolower($collection)) . 'Collection.php';

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
		try {
			$this->_collection->dropIndexes();
		}
		catch (Exception $e) {
			//	Collection probably didn't exist.
			//	This will create the collection.
			$this->_collection->insertOne([]);
			$this->_collection->drop();
		}
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
