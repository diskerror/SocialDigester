<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 8/24/2018
 * Time: 10:59 AM
 */

namespace Service;


use Laminas\Server\Reflection;

/**
 * Class Reflector
 *
 * This is used to to build help and about descriptions from the DocBlocks
 * of the Tasks for the CLI.
 *
 * @package Service
 */
class Reflector
{
	protected $_reflectedClass;

	public function __construct($class)
	{
		$this->_reflectedClass = Reflection::reflectClass($class);
	}

	public function getFormattedDescriptions(): array
	{
		$cmdDesc = $this->getCommandDescriptionArray();

		$desArr = [];

		if (array_key_exists('main', $cmdDesc)) {
			$desArr[] = sprintf("%-16s%s\n", '[default]', $cmdDesc['main']);
			unset($cmdDesc['main']);
		}

		if (array_key_exists('help', $cmdDesc)) {
			$desArr[] = sprintf("%-16s%s\n", 'help', $cmdDesc['help']);
			unset($cmdDesc['help']);
		}

		foreach ($cmdDesc as $cmd => $desc) {
			$desArr[] = strlen($cmd) < 16 ?
				sprintf("%-16s%s\n", $cmd, $desc) :
				sprintf("%-16s\n" . str_repeat(' ', 24) . "%s\n", $cmd, $desc);
		}

		return $desArr;
	}

	public function getCommandDescriptionArray(): array
	{
		$cmdDesc = [];
		foreach ($this->_reflectedClass->getMethods() as $method) {
			$methodName = $method->getName();

			switch ($methodName) {
				case '':
				case 'setDI':
				case 'getDI':
				case 'getEventsManager':
				case 'setEventsManager':
					continue 2;
			}

			$desc = $method->getDescription();

			$cmdDesc[substr($methodName, 0, -6)] =
				$methodName === $desc ?
					'' :
					wordwrap($desc, 72, "\n" . str_repeat(' ', 24));
		}

		ksort($cmdDesc, SORT_NATURAL | SORT_FLAG_CASE);

		return $cmdDesc;
	}
}
