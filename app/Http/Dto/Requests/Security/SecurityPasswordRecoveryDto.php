<?php

namespace App\Http\Dto\Requests\Security;

use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;

class SecurityPasswordRecoveryDto extends AbstractDto implements DtoInterface
{

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }
}
