<?php

namespace App\Http\Dto\Requests\Security;

use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;

class SecurityPasswordRecoveryDto extends AbstractDto implements DtoInterface
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
        return [];
    }
}
