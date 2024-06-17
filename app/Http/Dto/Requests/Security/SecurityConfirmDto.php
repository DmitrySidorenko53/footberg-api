<?php

namespace App\Http\Dto\Requests\Security;

use App\Http\Dto\Requests\AbstractDto;
use App\Http\Dto\Requests\DtoInterface;

class SecurityConfirmDto extends AbstractDto implements DtoInterface
{
    public int $userId;
    public string $code;

    public function rules(): array
    {
        return [
            'userId' => 'required|integer|exists:users,user_id',
            'code' => 'required|string|size:6',
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
