<?php

namespace Service;

class StopWords extends \PhpScience\TextRank\Tool\StopWords\StopWordsAbstract
{
	public function __construct(string $path)
	{
		$this->words = (require $path . '/StopWords.php')->toArray();
	}
}
