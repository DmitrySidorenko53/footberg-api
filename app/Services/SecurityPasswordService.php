<?php

namespace App\Services;

use App\Enums\EmailScopeEnum;
use App\Exceptions\InvalidIncomeTypeException;
use App\Helpers\EmailContentHelper;
use App\Http\Dto\Requests\Security\SecurityForgotPasswordDto;
use App\Http\Dto\Requests\Security\SecurityPasswordRecoveryDto;
use App\Http\Dto\Requests\Security\SecurityPasswordResetDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Interfaces\Service\SecurityPasswordServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use App\Jobs\SendEmailJob;
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


    /**
     * @throws InvalidIncomeTypeException
     */
    public function forgotPassword(DtoInterface $dto)
    {
        if (!$dto instanceof SecurityForgotPasswordDto) {
            throw new InvalidIncomeTypeException(__METHOD__, SecurityForgotPasswordDto::class);
        }

        /** @var User $user */
        $user = $this->userRepository->findBy('email', $dto->email)->first();

        if (!$user->isActiveOrNotDeleted()) {
            throw new NotFoundHttpException(__('exceptions.inactive'));
        }

        $code = $this->confirmationCodeService->refreshCode($user, 'reset');

        $reset = [
            'code' => $code,
            'recipient' => $user->email
        ];

        $email = EmailContentHelper::build($reset, EmailScopeEnum::RESET);
        dispatch(new SendEmailJob($email));

        return [
            'userId' => $user->user_id,
            'reset' => $reset
        ];
    }


    /**
     * @throws InvalidIncomeTypeException
     */
    public function resetPassword(DtoInterface $dto)
    {
        if (!$dto instanceof SecurityPasswordResetDto) {
            throw new InvalidIncomeTypeException(__METHOD__, SecurityPasswordResetDto::class);
        }

        /** @var User $user */
        $user = $this->userRepository->findById($dto->userId, 'codes');

        if (!$user->isActiveOrNotDeleted()) {
            throw new NotFoundHttpException(__('exceptions.inactive'));
        }

        $code = $user->getLastValidCode('reset');

        DB::transaction(function () use ($user, $dto, $code) {
            $this->confirmationCodeService->tryConfirmCode($code, $dto);

            $user->is_active = false;

            $this->userRepository->save($user);
        });

        return [
            'userId' => $user->user_id,
            'ableToLogin' => false,
        ];
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function recoveryPassword(DtoInterface $dto)
    {
        if (!$dto instanceof SecurityPasswordRecoveryDto) {
            throw new InvalidIncomeTypeException(__METHOD__, SecurityPasswordRecoveryDto::class);
        }

        $user = $this->userRepository->findById($dto->userId);

        if ($user->is_active) {
            throw new InvalidArgumentException(__('exceptions.active_on_recover'));
        }

        $user->password = Hash::make($dto->password, ['rounds' => 12]);
        $user->is_active = true;
        $user->last_login_at = Carbon::now()->format('Y-m-d H:i:s');


        $this->userRepository->save($user);

        return $this->securityTokenService->generateToken($user);
    }
}
