<?php

namespace Service;


class View extends \Phalcon\Mvc\View
{
	/**
	 * @param string $first
	 * @param string $second
	 * @param array  $params
	 *
	 * @return void
	 */
	public function render(string $first = '', string $second = '', array $params = null)
	{
		$dispatcher = $this->getDI()->getShared('dispatcher');

		if ('' === $first && '' === $second) {
			parent::render($dispatcher->getControllerName(), $dispatcher->getActionName());
		}
		elseif ('' === $second) {
			parent::render($dispatcher->getControllerName(), $first);
		}
		else {
			parent::render($first, $second, $params);
		}
	}

}
