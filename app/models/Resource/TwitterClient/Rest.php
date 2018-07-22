<?php

namespace Resource\TwitterClient;

use Resource\TwitterClient\ClientAbstract;

class Rest extends ClientAbstract
{

	/**
	 * Call a remote method.
	 * https://dev.twitter.com/streaming/public
	 *
	 * @param array $params OPTIONAL
	 *
	 * @return mixed
	 */
	public function __call($function, array $params = [])
	{
	}

}
