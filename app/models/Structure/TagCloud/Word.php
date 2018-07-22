<?php

namespace Structure\TagCloud;

use Diskerror\Typed\TypedClass;

class Word extends TypedClass
{
	protected $text            = '';

	protected $weight          = 0;

	protected $link            = '';

	protected $html            = '__class__\Structure\TagCloud\Html';

	protected $handlers        = [];

	protected $afterWordRender = '';
}
