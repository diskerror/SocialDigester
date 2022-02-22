<?php

namespace Structure;

use Resource\StopWords;

class TallyWordsStop extends TallyWords
{
	protected function _doTally(float $q, string $word): void
	{
		if (strlen($word) > 2 && !StopWords::contains(strtolower($word))) {
			if ($this->offsetExists($word)) {
				$this[$word] += $q;
			}
			else {
				$this[$word] = $q;
			}
		}
	}

}
