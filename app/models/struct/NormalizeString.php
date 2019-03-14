<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 2019-01-11
 * Time: 10:22
 */

namespace Structure;


use Diskerror\Typed\SAString;
use Normalizer;

class NormalizeString extends SAString
{
	public function set($in)
	{
		parent::set($in);
		$this->_value = preg_replace('/\s+/', ' ', Normalizer::normalize($this->_value, Normalizer::FORM_D));
	}

}
