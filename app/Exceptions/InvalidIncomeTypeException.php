<?php

namespace App\Exceptions;

use Exception;

class InvalidIncomeTypeException extends Exception
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
