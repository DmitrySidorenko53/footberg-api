<?php

namespace App\Http\Dto\Response;

class ApiCollection
{
    protected array $data;

    public function __construct($data, string $requireDto)
    {
        foreach ($data as $model) {
            $this->data[] = (new $requireDto($model))->build();
        }
    }

    public function getData(): array
    {
        return $this->data;
    }
}
