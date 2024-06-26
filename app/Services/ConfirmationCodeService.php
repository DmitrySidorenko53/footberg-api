<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use App\Interfaces\Repository\ConfirmationCodeRepositoryInterface;
use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Models\ConfirmationCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConfirmationCodeService implements ConfirmationCodeServiceInterface
{
    private ConfirmationCodeRepositoryInterface $confirmationCodeRepository;

    public function __construct(ConfirmationCodeRepositoryInterface $confirmationCodeRepository)
    {
        $this->confirmationCodeRepository = $confirmationCodeRepository;
    }

    /**
     * @throws ServiceException
     */
    public function createConfirmationCode($user, $scope = 'confirm'): array
    {
        if ($user && (!$user instanceof User)) {
            throw new InvalidArgumentException(get_class($this) . " create code method must receive a valid user model");
        }

        $code = (string)rand(100000, 999999);

        $confirmationCode = new ConfirmationCode();
        $confirmationCode->code_text = Hash::make($code);
        $confirmationCode->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $confirmationCode->valid_until = Carbon::now()->addHours(2)->format('Y-m-d H:i:s');
        $confirmationCode->user_id = $user->user_id;
        $confirmationCode->type = $scope;

        $isSuccess = $this->confirmationCodeRepository->save($confirmationCode);

        if (!$isSuccess) {
            throw new ServiceException('Error with create confirmation code');
        }

        return [
            'value' => $code,
            'created_at' => Carbon::parse($confirmationCode->created_at)->format('H:i:s d.m.Y'),
            'valid_until' => Carbon::parse($confirmationCode->valid_until)->format('H:i:s d.m.Y')
        ];
    }


    /**
     * @throws ServiceException
     */
    public function refreshCode($user, $scope = 'confirm')
    {
        if ($user && (!$user instanceof User)) {
            throw new InvalidArgumentException(get_class($this) . " refresh code method must receive a valid user model");
        }

        $userCodesIds = $user->codes()->where('type', $scope)->pluck('code_id')->toArray();

        $this->confirmationCodeRepository->updateWhereIn('code_id', $userCodesIds, [
            'is_expired' => true
        ]);

        return $this->createConfirmationCode($user, $scope);
    }

    /**
     * @throws ServiceException
     */
    public function tryConfirmCode($code, $dto): void
    {
        //todo dto must be PasswordResetDto or ConfirmDto
        if (!$this->isValid($code)) {
            throw new NotFoundHttpException('No valid codes for this user. Please refresh the code');
        }

        if (!Hash::check($dto->code, $code->code_text)) {
            throw new InvalidArgumentException('Invalid confirmation code');
        }

        $isConfirmed = $this->confirmCode($code);

        if (!$isConfirmed) {
            throw new ServiceException('Error while confirmed code');
        }
    }

    private function isValid($code): bool
    {
        if (!$code) {
            return false;
        }
        if (!$code instanceof ConfirmationCode) {
            return false;
        }
        if ($code->is_expired) {
            return false;
        }

        $validUntil = $code->valid_until;
        $now = Carbon::createFromFormat('Y-m-d H:i:s', now());

        if ($now->greaterThan($validUntil)) {
            return false;
        }

        return true;
    }

    private function confirmCode($code)
    {
        if ($code && (!$code instanceof ConfirmationCode)) {
            throw new InvalidArgumentException(get_class($this) . " refresh code method must receive a valid code model");
        }
        return $this->confirmationCodeRepository->update($code,
            [
                'confirmed_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'is_confirmed' => true,
                'is_expired' => true
            ]
        );
    }
}
