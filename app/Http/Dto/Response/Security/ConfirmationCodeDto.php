<?php

namespace App\Http\Dto\Response\Security;

use App\Http\Dto\Response\AbstractDto;

/**
 * Class ConfirmationCodeDto
 *
 * @property int $user_id
 * @property CodeDto $confirmation
 * @property string $recipient
 */
class ConfirmationCodeDto extends CodeDto
{

    public function __construct($code)
    {
        parent::__construct($code);
    }

    public function build($data = []): AbstractDto
    {
        return $this
            ->setProperty('user_id', $this->model->user_id)
            ->setDto('confirmation',
                CodeDto::class,
                $this->model,
                $data
            )
            ->setProperty('recipient', $this->model->user->email);
    }
}
