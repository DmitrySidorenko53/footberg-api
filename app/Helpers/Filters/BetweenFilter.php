<?php

namespace App\Helpers\Filters;

use App\Enums\Filters\WhereClausesEnum;
use App\Interfaces\FilterInterface;
use InvalidArgumentException;

class BetweenFilter extends AbstractFilter implements FilterInterface
{
    private array $values;

    /**
     * @param array $values
     * @param WhereClausesEnum $clause
     * @param $field
     * @param string $boolean
     */
    public function __construct(
        $field,
        array  $values,
        WhereClausesEnum $clause = WhereClausesEnum::WHERE_BETWEEN,
        string $boolean = 'and'
    )
    {
        $this->setValues($values);
        parent::__construct($clause, $field, $boolean);
    }


    public function addFilter($builder): void
    {
        if (!in_array($this->clause, WhereClausesEnum::requireTwoElementsArrayAsValueCases())) {
            return;
        }

        $builder
            ->{$this->clause->value}(
                $this->field,
                $this->values,
                $this->boolean
            );
    }

    private function setValues(array $values): void
    {
        if (sizeof($values) !== 2) {
            throw new InvalidArgumentException(__('exceptions.array_size'));
        }
        $this->values = $values;
    }
}
