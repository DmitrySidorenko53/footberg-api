<?php

namespace App\Http\Dto\Requests\Security;

use App\Http\Dto\Requests\AbstractDto;
use App\Models\User;


class SecurityRegisterDto extends AbstractDto
{
    public string $email;
    public string $password;

    public function rules(): array
    {
        return
            [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('validation.required', ['attribute' => 'email']),
            'email.string' => __('validation.string', ['attribute' => 'email']),
            'email.email' => __('validation.email', ['attribute' => 'email']),
            'email.exists' => __('validation.exists', ['attribute' => 'email', 'model' => User::class]),
            'email.max' => __('validation.max', ['attribute' => 'email', 'size' => 255]),

            'password.required' => __('validation.required', ['attribute' => 'password']),
            'password.string' => __('validation.string', ['attribute' => 'password']),
            'password.min' => __('validation.min', ['attribute' => 'password', 'size' => 8]),
            'password.max' => __('validation.max', ['attribute' => 'password', 'size' => 255]),
        ];
    }
}
