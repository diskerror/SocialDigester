<?php

namespace Logic;

class UserNameGroup extends TextGroup
{
	protected static function _normalizeString($s, $technique): string
	{
		return strtolower($s);
	}

}
