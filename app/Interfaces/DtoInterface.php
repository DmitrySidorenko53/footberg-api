<?php

namespace App\Interfaces;

interface DtoInterface
{
    public function rules(): array;
    public function messages(): array;
}
