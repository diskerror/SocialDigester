<?php

namespace TagCloud;

use Diskerror\Typed\TypedClass;

class Word extends TypedClass
{
	protected $text = '';

	protected $weight = 0;

	protected $link = '';

	protected $html = '__class__TagCloud\Html';

	protected $handlers = [];

	protected $afterWordRender = '';
}
