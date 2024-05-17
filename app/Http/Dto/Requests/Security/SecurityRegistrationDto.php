<?php

namespace App\Http\Dto\Requests\Security;

use App\Http\Dto\Requests\AbstractDto;

class SecurityRegistrationDto extends AbstractDto
{
    public $email;
    public $password;


    public function rules(): array
    {
        // TODO: Implement rules() method.
        return [];
    }
}
