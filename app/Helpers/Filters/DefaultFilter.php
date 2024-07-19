<?php

namespace App\Helpers\Filters;

use App\Enums\Filters\OperatorsEnum;
use App\Enums\Filters\WhereClausesEnum;
use App\Interfaces\FilterInterface;

class DefaultFilter extends AbstractFilter implements FilterInterface
{
    private OperatorsEnum $operator;
    private string|float|int|bool $value;

    public function __construct(
        $field,
        string|float|int|bool $value,
        $clause = WhereClausesEnum::WHERE,
        OperatorsEnum $operator = OperatorsEnum::EQUAL,
        string $boolean = 'and'
    )
    {
        $this->operator = $operator;
        $this->value = $value;
        parent::__construct($clause, $field, $boolean);
    }

    public function addFilter($builder): void
    {
        if (!in_array($this->clause, WhereClausesEnum::defaultCases())) {
            return;
        }

        if (in_array($this->operator, OperatorsEnum::likeOperators())) {
            $this->value = "%$this->value%";
        }

        $builder
            ->{$this->clause->value}(
                $this->field,
                $this->operator->value,
                $this->value,
                $this->boolean
            );
    }
}
