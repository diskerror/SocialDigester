<?php
/**
 * Created by PhpStorm.
 * User: 3525339
 * Date: 11/21/2018
 * Time: 4:27 PM
 */

namespace Resource;


use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Driver\Exception\CommandException;
use Structure\Config\Mongo;

class MongoCollectionManager
{
	protected $_collectionIndexDefs;
	protected $_collectionNames;
	protected $_db;

	public function __construct(Mongo $mongoConfig)
	{
		$this->_db                  = (new Client($mongoConfig->host))->{$mongoConfig->database};
		$this->_collectionIndexDefs = $mongoConfig->collections;
		$this->_collectionNames     = $mongoConfig->collections->keys();

		foreach ($this->_collectionIndexDefs as $collName => $indexDefs) {
			try {
				//  Throws exception if collection doesn't exist.
				$this->_db->command(['collStats' => $collName]);
			}
			catch (CommandException $e) {
				$this->doIndex($collName);
			}
		}
	}

	/**
	 * Mongo collections will be created if they don't exist and then (re)indexed.
	 * (Inserting a document creates a collection if it doesn't yet exist.)
	 *
	 * @param $collectionName
	 */
	public function doIndex(string $collectionName)
	{
		if (!in_array($collectionName, $this->_collectionNames)) {
			throw new \LogicException('Collection must be defined in "app/config/application.config.php".');
		}

		/** @var Collection $collection */
		$collection = $this->_db->{$collectionName};

		try {
			//  Throws exception if collection doesn't exist.
			$this->_db->command(['collStats' => $collectionName]);
		}
		catch (CommandException $e) {
			/** @var \MongoDB\InsertOneResult $inserted */
			$inserted = $collection->insertOne([]);
			$collection->deleteOne(['_id' => ['$eq' => $inserted->getInsertedId()]]);
		}

		$collection->dropIndexes();
		foreach ($this->_collectionIndexDefs[$collectionName] as $indexDef) {
			switch (count($indexDef)) {
				case 0;
					break;

				case 1:
					$collection->createIndex($indexDef['keys']);
					break;

				case 2:
					$collection->createIndex($indexDef['keys'], $indexDef['options']);
					break;

				default:
					throw new \LogicException('Bad index definition.');
			}
		}
	}

	public function getCollectionNames()
	{
		return $this->_collectionNames;
	}

	public function __get($collection)
	{
		if (!in_array($collection, $this->_collectionNames)) {
			throw new \LogicException('collection does not exist');
		}

		return $this->_db->{$collection};
	}

}
