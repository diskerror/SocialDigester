<?php

namespace Structure\Tweet;

use DateTimeZone;
use Diskerror\TypedBSON\DateTime;

class Created extends DateTime
{
	protected static $_days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

	public function __construct($time = 'now', $timezone = null)
	{
		if (is_string($time) && in_array(substr($time, 0, 3), self::$_days)) {
			//	Twitter date:	Wed Jun 10 05:24:16 + 0000 2020
			//	Twitter date:	Wed Jun 10 05:24:16 +0000 2020
			//		Create from format cannot parse timezone information.
			$matches = [];
			preg_match('/^(.+ )([+-]) ?(\d{4}) (.+)$/', $time, $matches);
			$time = self::createFromFormat(
				'* M d H:i:s Y',
				$matches[1] . $matches[4],
				new DateTimeZone($matches[2] . $matches[3])
			);
		}

		parent::__construct($time, $timezone);
	}
}
