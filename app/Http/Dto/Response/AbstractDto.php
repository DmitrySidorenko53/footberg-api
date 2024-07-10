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
        if (!$model) {
            return;
        }
        if (!$model instanceof $class) {
            throw new InvalidIncomeTypeException(__METHOD__, $class);
        }
        $this->model = $model;
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public static function create($model, $additionalData = []): AbstractDto
    {
        $instance = (new static($model))->build($additionalData);

        if (isset($instance->model)) {
            unset($instance->model);
        }

        return $instance;
    }


    abstract protected function build($additionalData = []): self;

    public function setCollection($key, $requireDto, $data): static
    {
        if ($data->isEmpty()) {
            return $this;
        }

        $collection = new ApiCollection($data, $requireDto);

        $this->{$key} = $collection->getData();
        return $this;
    }

    public function setDto($key, $requireDto, $model, $additionalData = []): static
    {
        if (!$model) {
            return $this;
        }
        $this->{$key} = $requireDto::create($model, $additionalData);
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
        $datetime = Carbon::parse($value)->format($format);

        if (!Carbon::canBeCreatedFromFormat($datetime, $format)) {
            return $this;
        }

        $this->{$key} = $datetime;
        return $this;
    }

    public function getProperty($key)
    {
        return $this->{$key};
    }
}
