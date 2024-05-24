<?php

namespace App\Http\Dto\Requests\Security;

use App\Http\Dto\Requests\AbstractDto;

class SecurityRegistrationDto extends AbstractDto
{
    public $email;
    public $password;

//40RyA2WmGkbxHRgC3cAyRUzePQUHnkup

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
