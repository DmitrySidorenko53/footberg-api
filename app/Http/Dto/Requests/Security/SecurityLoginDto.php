<?php

namespace App\Http\Dto\Requests\Security;

use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;

class SecurityLoginDto extends AbstractDto implements DtoInterface
{
    public string $email;
    public string $password;
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|exists:users,email|max:255',
            'password' => 'required|string|min:8|max:255',
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
