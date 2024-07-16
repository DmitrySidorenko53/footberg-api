<?php

namespace App\Helpers\Filters;

use App\Enums\Filters\WhereClausesEnum;

class LogicFilter extends AbstractFilter
{

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
