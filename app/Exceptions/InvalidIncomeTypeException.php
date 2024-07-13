<?php

namespace App\Exceptions;

use InvalidArgumentException;

class InvalidIncomeTypeException extends InvalidArgumentException
{
    public function __construct($scope, $shouldReceive)
    {
        $bindings = [
            'scope' => $scope,
            'type' => $shouldReceive
        ];

        $message = __('exceptions.income.type.invalid', $bindings);

        parent::__construct($message);
    }

}
