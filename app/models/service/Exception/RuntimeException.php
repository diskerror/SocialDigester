<?php
/**
 * Created by PhpStorm.
 * User: 3525339
 * Date: 8/29/2018
 * Time: 1:02 PM
 */

namespace Service\Exception;

use Throwable;

class RuntimeException extends \RuntimeException
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
