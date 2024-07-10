<?php

namespace App\Http\Dto\Response\Security;

use App\Helpers\StringGenerator;
use App\Http\Dto\Response\AbstractDto;
use App\Models\SecurityToken;

/**
 * Class GeneratedTokenDto
 *
 * @property string $token
 * @property string $created_at
 * @property string $valid_until
 */
class GeneratedTokenDto extends AbstractDto
{

    public function __construct($token)
    {
        parent::__construct(SecurityToken::class, $token);
    }
    protected function build($additionalData = []): AbstractDto
    {
        return $this
            ->setProperty('token', StringGenerator::getSecurityTokenStart() . $this->model->token)
            ->setDateTime('created_at', $this->model->created_at)
            ->setDateTime('valid_until', $this->model->valid_until);
    }
}
