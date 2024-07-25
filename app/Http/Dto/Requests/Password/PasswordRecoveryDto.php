<?php

namespace App\Http\Dto\Requests\Password;

use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Models\User;

class PasswordRecoveryDto extends AbstractDto implements DtoInterface
{
    public int $userId;
    public string $password;

    public function rules(): array
    {
        return [
            'userId' => 'required|integer|exists:users,user_id',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'userId.required' => __('validation.required', ['attribute' => 'userId']),
            'userId.integer' => __('validation.integer', ['attribute' => 'userId']),
            'userId.exists' => __('validation.exists', ['attribute' => 'userId', 'model' => User::class]),

            'password.required' => __('validation.required', ['attribute' => 'password']),
            'password.string' => __('validation.string', ['attribute' => 'password']),
            'password.min' => __('validation.min', ['attribute' => 'password', 'size' => '8']),
            'password.confirmed' => __('validation.confirmed', ['attribute' => 'password']),
        ];
    }
}
