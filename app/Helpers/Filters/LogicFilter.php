<?php

namespace App\Helpers\Filters;

use App\Enums\Filters\WhereClausesEnum;
use App\Interfaces\FilterInterface;

class LogicFilter extends AbstractFilter implements FilterInterface
{

    public function __construct(
        $field,
        WhereClausesEnum $clause = WhereClausesEnum::WHERE_NULL,
        $boolean = 'and'
    )
    {
        parent::__construct($clause, $field, $boolean);
    }

    public function addFilter($builder): void
    {
        if (!in_array($this->clause, WhereClausesEnum::requireOnlyFieldCases())) {
            return;
        }

        $builder
            ->{$this->clause->value}(
                $this->field,
                $this->boolean
            );
    }
}
