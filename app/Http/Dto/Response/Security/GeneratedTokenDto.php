<?php

namespace App\Http\Dto\Response\Security;

use App\Helpers\StringGenerator;
use App\Http\Dto\Response\AbstractDto;
use App\Models\SecurityToken;

class GeneratedTokenDto extends AbstractDto
{

    public function __construct($token)
    {
        parent::__construct(SecurityToken::class, $token);
    }
    public function build($data = []): AbstractDto
    {
        return $this
            ->setProperty('token', StringGenerator::getSecurityTokenStart() . $this->model->token)
            ->setDateTime('created_at', $this->model->created_at)
            ->setDateTime('valid_until', $this->model->valid_until);
    }
}
