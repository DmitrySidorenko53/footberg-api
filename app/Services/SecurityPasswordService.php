<?php

namespace App\Services;

use App\Enums\EmailScope;
use App\Helpers\EmailContentHelper;
use App\Http\Dto\Requests\Security\SecurityForgotPasswordDto;
use App\Http\Dto\Requests\Security\SecurityPasswordRecoveryDto;
use App\Http\Dto\Requests\Security\SecurityPasswordResetDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Interfaces\Service\SecurityPasswordServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use App\Jobs\SendEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SecurityPasswordService implements SecurityPasswordServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private ConfirmationCodeServiceInterface $confirmationCodeService;
    private SecurityTokenServiceInterface $securityTokenService;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param ConfirmationCodeServiceInterface $confirmationCodeService
     * @param SecurityTokenServiceInterface $securityTokenService
     */
    public function __construct(
        UserRepositoryInterface          $userRepository,
        ConfirmationCodeServiceInterface $confirmationCodeService,
        SecurityTokenServiceInterface    $securityTokenService
    )
    {
        $this->userRepository = $userRepository;
        $this->confirmationCodeService = $confirmationCodeService;
        $this->securityTokenService = $securityTokenService;
    }


    public function forgotPassword(DtoInterface $dto)
    {
        if (!$dto instanceof SecurityForgotPasswordDto) {
            throw new InvalidArgumentException(get_class($this) . " confirm method must receive a SecurityForgotPasswordDto");
        }

        /** @var User $user */
        $user = $this->userRepository->findBy('email', $dto->email)->first();

        if (!$user->isActiveOrNotDeleted()) {
            throw new NotFoundHttpException('User is inactive or deleted');
        }

        $code = $this->confirmationCodeService->refreshCode($user, 'reset');

        $reset = [
            'code' => $code,
            'recipient' => $user->email
        ];

        $email = EmailContentHelper::build($reset, EmailScope::RESET);
        dispatch(new SendEmail($email));

        return [
            'userId' => $user->user_id,
            'reset' => $reset
        ];
    }


    public function resetPassword(DtoInterface $dto)
    {
        if (!$dto instanceof SecurityPasswordResetDto) {
            throw new InvalidArgumentException(get_class($this) . " reset password method must receive a SecurityPasswordResetDto");
        }

        /** @var User $user */
        $user = $this->userRepository->findById($dto->userId, 'codes');

        if (!$user->isActiveOrNotDeleted()) {
            throw new NotFoundHttpException('User is inactive or deleted');
        }

        $code = $user->getLastValidCode('reset');

        DB::transaction(function () use ($user, $dto, $code) {
            $this->confirmationCodeService->tryConfirmCode($code, $dto);

            $user->is_active = false;
            $user->password = '';

            $this->userRepository->save($user);
        });

        return [
            'userId' => $user->user_id,
            'ableToLogin' => false,
        ];
    }

    public function recoveryPassword(DtoInterface $dto)
    {
        if (!$dto instanceof SecurityPasswordRecoveryDto) {
            throw new InvalidArgumentException(get_class($this) . " reset password method must receive a SecurityPasswordResetDto");
        }

        $user = $this->userRepository->findById($dto->userId);

        if ($user->is_active) {
            throw new InvalidArgumentException('Unable to recover password on active account');
        }

        $user->password = Hash::make($dto->password, ['rounds' => 12]);
        $user->is_active = true;
        $user->last_login_at = Carbon::now()->format('Y-m-d H:i:s');


        $this->userRepository->save($user);

        return $this->securityTokenService->generateToken($user);
    }
}
