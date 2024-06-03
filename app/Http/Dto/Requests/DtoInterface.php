<?php

namespace App\Http\Dto\Requests;

interface DtoInterface
{
    public function rules(): array;
    public function messages(): array;
}
