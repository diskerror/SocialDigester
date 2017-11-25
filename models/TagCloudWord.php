<?php

class TagCloudWord extends Diskerror\Typed\TypedClass
{
	protected $text = '';
	protected $weight = 0;
	protected $link = '';
	protected $html = '__class__TagCloudHtml';
	protected $handlers = [];
	protected $afterWordRender = '';
}
