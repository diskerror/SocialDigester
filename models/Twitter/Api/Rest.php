<?php

namespace Twitter\Api;

class Rest extends Twitter\Api\ApiAbstract
{

	/**
	 * Call a remote method.
	 * https://dev.twitter.com/streaming/public
	 *
	 * @param array $params OPTIONAL
	 * @return mixed
	 */
	public function __call($function, array $params = [])
	{
	}

}
