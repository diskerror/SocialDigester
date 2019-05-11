<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 7/17/18
 * Time: 5:44 PM
 */

namespace Resource;

use Diskerror\Typed\ArrayOptions as AO;

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

	/**
	 * MongoCollection constructor.
	 */
	public function __construct() { }

	abstract public function doIndex(int $expire=0);

	public function insertOne($document, array $options = [])
	{
		if ($document instanceof \Diskerror\Typed\TypedClass) {
			$argOptions = $document->getArrayOptions();
			$document->setArrayOptions(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::SWITCH_ID | AO::TO_BSON_DATE);

			$res = $this->getClient()->insertOne($document->toArray(), $options);

			$document->setArrayOptions($argOptions);
		}
		else {
			$res = $this->getClient()->insertOne($document, $options);
		}

		return $res;
	}

	/**
	 * @return \MongoDB\Collection
	 */
	public function getClient()
	{
		if (!isset($this->_client)) {
			$this->_client = \Phalcon\Di::getDefault()->getShared('mongo')->{$this->_collection};
		}
		return $this->_client;
	}

	public function insertMany($document, array $options = [])
	{
		if ($document instanceof \Diskerror\Typed\TypedArray) {
			$argOptions = $document->getArrayOptions();
			$document->setArrayOptions(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::SWITCH_ID | AO::TO_BSON_DATE);

			$res = $this->getClient()->insertMany($document->toArray(), $options);

			$document->setArrayOptions($argOptions);
		}
		else {
			$res = $this->getClient()->insertOne($document, $options);
		}

		return $res;
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
				throw new \InvalidArgumentException('too many arguments');
		}
	}

	public function find($filter = [], array $options = [])
	{
		$res = $this->getClient()->find($filter, $options);
		return new \Diskerror\Typed\TypedArray($res, $this->_class);
	}

	public function findOne($filter = [], array $options = [])
	{
		$res = $this->getClient()->findOne($filter, $options);
		return new $this->_class($res);
	}
}
