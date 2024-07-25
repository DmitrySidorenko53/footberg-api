<?php

namespace App\Services;

use App\Enums\ConfirmationCodeScopeEnum;
use App\Enums\EmailScopeEnum;
use App\Exceptions\InvalidIncomeTypeException;
use App\Helpers\EmailContentHelper;
use App\Http\Dto\Requests\Code\CodeDto;
use App\Http\Dto\Requests\Password\ChangePasswordDto;
use App\Http\Dto\Requests\Password\ForgotPasswordDto;
use App\Http\Dto\Requests\Password\PasswordRecoveryDto;
use App\Http\Dto\Response\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Interfaces\Service\SecurityPasswordServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use App\Jobs\SendEmailJob;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Dto\Response\Security\CodeDto as ResponseDto;

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
    public function forgotPassword(DtoInterface $dto): ResponseDto
    {
        if (!$dto instanceof ForgotPasswordDto) {
            throw new InvalidIncomeTypeException(__METHOD__, ForgotPasswordDto::class);
        }

        /** @var User $user */
        $user = $this->userRepository->findBy('email', $dto->email)->first();

        if (!$user->isActiveOrNotDeleted()) {
            throw new NotFoundHttpException(__('exceptions.inactive'));
        }

        $code = $this->confirmationCodeService->refreshCode($user, ConfirmationCodeScopeEnum::RESET);


        $email = EmailContentHelper::build($code, EmailScopeEnum::RESET);
        dispatch(new SendEmailJob($email));

        return $code;
    }


    /**
     * @throws InvalidIncomeTypeException
     */
    public function resetPassword(DtoInterface $dto): void
    {
        if (!$dto instanceof CodeDto) {
            throw new InvalidIncomeTypeException(__METHOD__, CodeDto::class);
        }

        /** @var User $user */
        $user = $this->userRepository->findById($dto->userId, 'codes');

        if (!$user->isActiveOrNotDeleted()) {
            throw new NotFoundHttpException(__('exceptions.inactive'));
        }

        $code = $user->getLastValidCode(ConfirmationCodeScopeEnum::RESET);

        $codeCandidate = $dto->code;

        DB::transaction(function () use ($codeCandidate, $user, $code) {
            $this->confirmationCodeService->tryConfirmCode($code, $codeCandidate);

            $user->is_active = false;

            $this->userRepository->save($user);
        });
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function recoveryPassword(DtoInterface $dto): AbstractDto
    {
        if (!$dto instanceof PasswordRecoveryDto) {
            throw new InvalidIncomeTypeException(__METHOD__, PasswordRecoveryDto::class);
        }

        $user = $this->userRepository->findById($dto->userId);

        if ($user->is_active) {
            throw new InvalidArgumentException(__('exceptions.active_on_recover'));
        }

        if (Hash::check($dto->password, $user->password)) {
            throw new InvalidArgumentException(__('exceptions.duplicate_password'));
        }

        $user->password = Hash::make($dto->password, ['rounds' => 12]);
        $user->is_active = true;
        $user->last_login_at = now()->format('Y-m-d H:i:s');


        $this->userRepository->save($user);

        return $this->securityTokenService->generateToken($user);
    }

    public function changePassword(DtoInterface $dto, Authenticatable $user): AbstractDto
    {
        if (!$dto instanceof ChangePasswordDto) {
            throw new InvalidIncomeTypeException(__METHOD__, ChangePasswordDto::class);
        }

        if (!$user instanceof User) {
            throw new InvalidIncomeTypeException(__METHOD__, User::class);
        }

        if (!Hash::check($dto->currentPassword, $user->password)) {
            throw new InvalidArgumentException(__('exceptions.incorrect_password'));
        }

        if (Hash::check($dto->newPassword, $user->password)) {
            throw new InvalidArgumentException(__('exceptions.duplicate_password'));
        }

        $user->password = Hash::make($dto->newPassword, ['rounds' => 12]);
        $user->last_login_at = now()->format('Y-m-d H:i:s');
        $this->userRepository->save($user);

        return $this->securityTokenService->generateToken($user);
    }
}
