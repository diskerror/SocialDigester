<?php

namespace Tally;

abstract class AbstractTally
{
    protected $_twit;
    protected $_tally;

    /**
     * @param \MongoDB\Client $mongo
     */
    function __construct(\MongoDB\Client $mongo)
    {
        $this->_twit = $mongo->feed->twitter;
        $this->_tally = [];
    }

    public function doTally($word)
    {
        if (array_key_exists($word, $this->_tally)) {
            ++$this->_tally[$word];
        }
        else {
            $this->_tally[$word] = 1;
        }
    }

}
