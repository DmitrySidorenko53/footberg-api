<?php

namespace App\Http\Dto\Response\Security;

use App\Http\Dto\Response\AbstractDto;

/**
 * Class ResetPasswordCodeDto
 *
 * @property int $user_id
 * @property CodeDto $reset_password
 * @property string $recipient
 */
class ResetPasswordCodeDto extends CodeDto
{

    public function __construct($code)
    {
        parent::__construct($code);
    }

    public function build($data = []): AbstractDto
    {
        return $this
            ->setProperty('user_id', $this->model->user_id)
            ->setDto('reset_password',
                CodeDto::class,
                $this->model,
                $data
            )
            ->setProperty('recipient', $this->model->user->email);
    }
}
