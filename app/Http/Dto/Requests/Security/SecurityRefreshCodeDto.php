<?php

namespace App\Http\Dto\Requests\Security;

use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Models\User;

class SecurityRefreshCodeDto extends AbstractDto implements DtoInterface
{
    public int $userId;

    public function rules(): array
    {
        return [
            'userId' => 'required|integer|exists:users,user_id',
        ];
    }

    public function messages(): array
    {
        return [
            'userId.required' => __('validation.required', ['attribute' => 'userId']),
            'userId.integer' => __('validation.integer', ['attribute' => 'userId']),
            'userId.exists' => __('validation.exists', ['attribute' => 'userId', 'model' => User::class]),
        ];
    }
}
