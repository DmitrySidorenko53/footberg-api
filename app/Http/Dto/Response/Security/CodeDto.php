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

    protected function build($additionalData = []): AbstractDto
    {
        return $this
            ->setProperty('code', (int) $additionalData['code'])
            ->setDateTime('created_at', $this->model->created_at, 'H:i:s d.m.Y')
            ->setDateTime('valid_until', $this->model->valid_until, 'H:i:s d.m.Y');
    }

    //todo unset message from object
    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
