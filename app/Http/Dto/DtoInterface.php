<?php

namespace App\Http\Dto;

interface DtoInterface
{
    public function rules(): array;
    public function messages(): array;
}
