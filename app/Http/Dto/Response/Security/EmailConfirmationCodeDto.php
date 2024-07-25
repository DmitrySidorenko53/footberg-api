<?php

namespace App\Http\Dto\Response\Security;

use App\Http\Dto\Response\AbstractDto;

/**
 * Class EmailConfirmationCodeDto
 *
 * @property int $user_id
 * @property CodeDto $email_confirmation
 * @property string $recipient
 */
class EmailConfirmationCodeDto extends CodeDto
{

    public function __construct($code)
    {
        parent::__construct($code);
    }

    protected function build($additionalData = []): AbstractDto
    {
        return $this
            ->setProperty('user_id', $this->model->user_id)
            ->setDto('email_confirmation',
                CodeDto::class,
                $this->model,
                $additionalData
            )
            ->setProperty('recipient', $this->model->user->email)
            ->setProperty('message', __('code.sent_email_confirmation_code'));
    }
}
