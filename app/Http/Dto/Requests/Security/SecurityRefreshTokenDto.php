<?php

namespace App\Http\Dto\Requests\Security;

use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;

class SecurityRefreshTokenDto extends AbstractDto implements DtoInterface
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
        return [];
    }
}
