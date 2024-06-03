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
                'email' => 'required',
                'password' => 'required',
            ];
    }

    public function messages(): array
    {
        return [];
    }
}
