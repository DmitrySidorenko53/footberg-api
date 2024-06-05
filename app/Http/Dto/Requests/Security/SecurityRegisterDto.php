<?php

namespace App\Http\Dto\Requests\Security;

use App\Http\Dto\Requests\AbstractDto;


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
        return [];
    }
}
