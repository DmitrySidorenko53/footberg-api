<?php

namespace App\Http\Dto\Response\Security;

use App\Http\Dto\Response\AbstractDto;

/**
 * Class PhoneNumberCodeDto
 *
 * @property int $user_id
 * @property CodeDto $phone_confirmation
 * @property string $phone_number
 */
class PhoneNumberCodeDto extends CodeDto
{

    public function __construct($code)
    {
        parent::__construct($code);
    }

    protected function build($additionalData = []): AbstractDto
    {
        return $this
            ->setProperty('user_id', $this->model->user_id)
            ->setDto('phone_confirmation',
                CodeDto::class,
                $this->model,
                $additionalData
            )
            ->setProperty('phone_number', $this->model->user->security_phone_number);
    }
}
