<?php

namespace App\Http\Dto\Requests\Account;

use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Models\User;

class AccountLoginDto extends AbstractDto implements DtoInterface
{
    public string $email;
    public string $password;
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('validation.required', ['attribute' => 'email']),
            'email.email' => __('validation.email', ['attribute' => 'email']),
            'email.string' => __('validation.string', ['attribute' => 'email']),
            'email.exists' => __('validation.exists', ['attribute' => 'email', 'model' => User::class]),

            'password.required' => __('validation.required', ['attribute' => 'password']),
            'password.string' => __('validation.string', ['attribute' => 'password']),
        ];
    }
}
