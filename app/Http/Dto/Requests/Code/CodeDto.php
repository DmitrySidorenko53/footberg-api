<?php

namespace App\Http\Dto\Requests\Code;

use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Models\User;

class CodeDto extends AbstractDto implements DtoInterface
{

    public int $userId;
    public string $code;

    public function rules(): array
    {
        return [
            'userId' => 'required|integer|exists:users,user_id',
            'code' => 'required|numeric|digits:6',
        ];
    }

    public function messages(): array
    {
        return [
            'userId.required' => __('validation.required', ['attribute' => 'userId']),
            'userId.integer' => __('validation.integer', ['attribute' => 'userId']),
            'userId.exists' => __('validation.exists', ['attribute' => 'userId', 'model' => User::class]),

            'code.required' => __('validation.required', ['attribute' => 'code']),
            'code.numeric' => __('validation.numeric', ['attribute' => 'code']),
            'code.digits' => __('validation.digits', ['attribute' => 'code', 'digits' => 6]),
        ];
    }
}
