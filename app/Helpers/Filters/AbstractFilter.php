<?php

namespace App\Helpers\Filters;

use App\Enums\Filters\WhereClausesEnum;

abstract class AbstractFilter
{
    protected WhereClausesEnum $clause;
    protected string $field;
    protected string $boolean;

    /**
     * @param $clause
     * @param $field
     * @param $boolean
     */
    public function __construct($clause, $field, $boolean)
    {
        $this->clause = $clause;
        $this->field = $field;
        $this->boolean = $boolean;
    }
}
