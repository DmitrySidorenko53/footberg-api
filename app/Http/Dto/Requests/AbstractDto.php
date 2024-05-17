<?php

namespace App\Http\Dto\Requests;

use App\Http\Dto\DtoInterface;

abstract class AbstractDto implements DtoInterface
{
    public array $data;
    public $validator;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}
