<?php

namespace App\Services\Impl;

use App\Exceptions\ServiceException;
use App\Mail\AppMail;
use App\Models\ConfirmationCode;
use App\Repositories\ConfirmationCodeRepositoryInterface;
use App\Services\ConfirmationCodeServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ConfirmationCodeService implements ConfirmationCodeServiceInterface
{
    private ConfirmationCodeRepositoryInterface $confirmationCodeRepository;

    public function __construct(ConfirmationCodeRepositoryInterface $confirmationCodeRepository)
    {
        $this->confirmationCodeRepository = $confirmationCodeRepository;
    }

    public function sendEmail($confirmationCode, $email): void
    {
        Mail::to($email)
            ->send(new AppMail(
                [
                    'title' => 'Confirm your account',
                    'confirmationCode' => $confirmationCode,
                    'recipient' => $email
                ],
                'confirmation-mail'));
    }


    public function createConfirmationCode($user): array
    {
        $code = (string)rand(100000, 999999);

        $confirmationCode = new ConfirmationCode();
        $confirmationCode->code_text = Hash::make($code);
        $confirmationCode->created_at = Carbon::now(3)->format('Y-m-d H:i:s');
        $confirmationCode->valid_until = Carbon::now(3)->addHours(2)->format('Y-m-d H:i:s');
        $confirmationCode->user_id = $user->user_id;

        $isSuccess = $this->confirmationCodeRepository->save($confirmationCode);

        if (!$isSuccess) {
            throw new ServiceException('Error with create confirmation code');
        }

        return [
            'code' => $code,
            'created_at' => $confirmationCode->created_at,
            'valid_until' => $confirmationCode->valid_until
        ];
    }
}
