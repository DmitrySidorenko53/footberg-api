<?php

namespace App\Http\Dto\Requests\Security;

use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Models\User;

class SecurityForgotPasswordDto extends AbstractDto implements DtoInterface
{
    public string $email;

    public function rules(): array
    {
        return [
            'email' => 'required|string|email|exists:users,email'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('validation.required', ['attribute' => 'email']),
            'email.string' => __('validation.string', ['attribute' => 'email']),
            'email.email' => __('validation.email'),
            'email.exists' => __('validation.exists', ['attribute' => 'email', 'model' => User::class]),
        ];
    }
}
