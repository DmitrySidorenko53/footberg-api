<?php

namespace App\Http\Dto\Requests\Security;

use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Models\User;

class SecurityCodeDto extends AbstractDto implements DtoInterface
{

    public int $userId;
    public string $code;

    public function rules(): array
    {
        return [
            'userId' => 'required|integer|exists:users,user_id',
            'code' => 'required|string|size:6',
        ];
    }

    public function messages(): array
    {
        return [
            'userId.required' => __('validation.required', ['attribute' => 'userId']),
            'userId.integer' => __('validation.integer', ['attribute' => 'userId']),
            'userId.exists' => __('validation.exists', ['attribute' => 'userId', 'model' => User::class]),

            'code.required' => __('validation.required', ['attribute' => 'code']),
            'code.string' => __('validation.string', ['attribute' => 'code']),
            'code.size' => __('validation.size', ['attribute' => 'code', 'size' => 6]),
        ];
    }
}
