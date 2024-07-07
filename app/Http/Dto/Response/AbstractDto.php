<?php

namespace App\Http\Dto\Response;

use App\Exceptions\InvalidIncomeTypeException;
use Carbon\Carbon;

abstract class AbstractDto
{
    protected $model;
    /**
     * @throws InvalidIncomeTypeException
     */
    public function __construct($class, $model)
    {
        if (!$model instanceof $class) {
            throw new InvalidIncomeTypeException(__METHOD__, $class);
        }
        $this->model = $model;
    }

    abstract public function build($data): self;

    /**
     * @throws InvalidIncomeTypeException
     */
    public function setCollection($key, $collection): static
    {
        if (!$collection) {
            return $this;
        }

        if (!$collection instanceof ApiCollection){
            throw new InvalidIncomeTypeException(__METHOD__, ApiCollection::class);
        }

        $this->setProperty($key, $collection->getData());
        return $this;
    }

    public function setProperty($key, $value): static
    {
        if (!$value) {
            return $this;
        }
        $this->{$key} = $value;
        return $this;
    }

    public function setDateTime($key, $value, $format = 'Y-m-d H:i:s'): static
    {
       if (!Carbon::canBeCreatedFromFormat($value, $format)) {
           return $this;
       }
       $this->{$key} = $value;
       return $this;
    }

    public function getProperty($key)
    {
        return $this->{$key};
    }
}
