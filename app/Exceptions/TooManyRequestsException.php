<?php

namespace App\Exceptions;

use Exception;

class TooManyRequestsException extends Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }
}
