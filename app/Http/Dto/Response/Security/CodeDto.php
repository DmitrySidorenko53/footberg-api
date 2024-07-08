<?php

namespace App\Http\Dto\Response\Security;

use App\Http\Dto\Response\AbstractDto;
use App\Models\ConfirmationCode;

/**
 * Class CodeDto
 *
 * @property int $code
 * @property string $created_at
 * @property string $valid_until$
 */
class CodeDto extends AbstractDto
{

    public function __construct($code)
    {
        parent::__construct(ConfirmationCode::class, $code);
    }

    public function build($data = []): AbstractDto
    {
        return $this
            ->setProperty('code', (int) $data['code'])
            ->setDateTime('created_at', $this->model->created_at, 'H:i:s d.m.Y')
            ->setDateTime('valid_until', $this->model->valid_until, 'H:i:s d.m.Y');
    }
}
