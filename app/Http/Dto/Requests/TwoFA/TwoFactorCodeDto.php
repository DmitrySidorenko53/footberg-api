<?php

namespace App\Http\Dto\Requests\TwoFA;

use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;

class TwoFactorCodeDto extends AbstractDto implements DtoInterface
{
    public string $code;

    public function rules(): array
    {
        return [
            'code' => 'required|numeric|digits:6',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => __('validation.required', ['attribute' => 'code']),
            'code.numeric' => __('validation.numeric', ['attribute' => 'code']),
            'code.digits' => __('validation.digits', ['attribute' => 'code', 'digits' => 6]),
        ];
    }
}
