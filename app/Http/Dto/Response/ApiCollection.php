<?php

namespace App\Http\Dto\Response;

class ApiCollection
{
    protected array $data;

    public function __construct($data, $requireDto, $additionalData = [])
    {
        foreach ($data as $model) {
            $this->data[] = $requireDto::create($model, $additionalData);
        }
    }

    public function getData(): array
    {
        return $this->data;
    }
}
