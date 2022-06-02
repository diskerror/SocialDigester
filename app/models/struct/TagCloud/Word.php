<?php

namespace Structure\TagCloud;

use Diskerror\TypedBSON\TypedClass;

class Word extends TypedClass
{
	protected $text = '';

	protected $weight = 0;

	protected $link = '';

	protected $html = [Html::class];

	protected $handlers = [];

	protected $afterWordRender = '';
}
