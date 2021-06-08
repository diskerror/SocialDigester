<?php

namespace Structure;

use Diskerror\Typed\SAStringTrim;
use Normalizer;

class NormalizeString extends SAStringTrim
{
	public function set($in)
	{
		parent::set($in);
		$this->_value = preg_replace('/\s+/', ' ', Normalizer::normalize($this->_value, Normalizer::FORM_D));
	}

}
