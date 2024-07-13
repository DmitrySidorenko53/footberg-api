<?php

namespace App\Exceptions;

use InvalidArgumentException;

class NotInArrayException extends InvalidArgumentException
{

    public function __construct($requiredArray)
    {
        $bindings = [
            'array' => $requiredArray
        ];

        $message = __('exceptions.in_array', $bindings);

        parent::__construct($message);
    }
}
