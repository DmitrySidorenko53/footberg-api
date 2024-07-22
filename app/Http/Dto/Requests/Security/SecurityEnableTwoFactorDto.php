<?php

namespace App\Http\Dto\Requests\Security;

use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;

class SecurityEnableTwoFactorDto extends AbstractDto implements DtoInterface
{

    public function rules(): array
    {
        // TODO: Implement rules() method.
    }

    public function messages(): array
    {
        // TODO: Implement messages() method.
    }
}
