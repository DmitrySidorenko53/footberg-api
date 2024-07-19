<?php

namespace App\Helpers\Filters;

use App\Enums\Filters\WhereClausesEnum;
use App\Interfaces\FilterInterface;

class InArrayFilter extends AbstractFilter implements FilterInterface
{
    private array $values;

    /**
     * @param array $values
     * @param WhereClausesEnum $clause
     * @param $field
     * @param string $boolean
     */
    public function __construct(
        array  $values,
               $field,
        WhereClausesEnum $clause = WhereClausesEnum::WHERE_IN,
        string $boolean = 'and'
    )
    {
        $this->values = $values;
        parent::__construct($clause, $field, $boolean);
    }

    public function addFilter($builder): void
    {
        if (!in_array($this->clause, WhereClausesEnum::requireArrayAsValueCases())) {
            return;
        }

        $builder
            ->{$this->clause->value}(
                $this->field,
                $this->values,
                $this->boolean
            );
    }
}
