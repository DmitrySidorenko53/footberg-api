<?php

namespace App\Services;


use App\Enums\EmailScope;
use App\Exceptions\ServiceException;
use App\Helpers\EmailContentHelper;
use App\Http\Dto\Requests\Security\SecurityConfirmDto;
use App\Http\Dto\Requests\Security\SecurityLoginDto;
use App\Http\Dto\Requests\Security\SecurityRefreshCodeDto;
use App\Http\Dto\Requests\Security\SecurityRegisterDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Interfaces\Service\SecurityServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use App\Jobs\SendEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class SecurityService implements SecurityServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private ConfirmationCodeServiceInterface $confirmationCodeService;
    private SecurityTokenServiceInterface $securityTokenService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        ConfirmationCodeServiceInterface $confirmationCodeService,
        SecurityTokenServiceInterface $securityTokenService)
    {
        $this->userRepository = $userRepository;
        $this->confirmationCodeService = $confirmationCodeService;
        $this->securityTokenService = $securityTokenService;
    }

    public function register($dto)
    {
        if (!$dto instanceof SecurityRegisterDto) {
            throw new InvalidArgumentException(get_class($this) . " register method must receive a SecurityRegisterDto");
        }
        $user = new User();
        $user->email = $dto->email;
        $user->password = Hash::make($dto->password, ['rounds' => 12]);
        $user->register_at = Carbon::now(3)->format('Y-m-d H:i:s');

        $code = [];

        try {
            DB::beginTransaction();

            $isSuccess = $this->userRepository->save($user);

            if (!$isSuccess) {
                throw new ServiceException('Error registering user');
            }
            $code = $this->confirmationCodeService->createConfirmationCode($user);

            DB::commit();
        } catch (Throwable) {
            DB::rollBack();
        }

        $confirmation = [
            'confirmation' => $code,
            'recipient' => $user->email
        ];

        $email = EmailContentHelper::build($confirmation, EmailScope::CONFIRMATION);

        dispatch(new SendEmail($email));

        return [
            'user_id' => $user->user_id,
            'email' => $user->email
        ];
    }

    public function login($dto)
    {
        if (!$dto instanceof SecurityLoginDto) {
            throw new InvalidArgumentException(get_class($this) . " login method must receive a SecurityLoginDto");
        }

        /** @var User $user */
        $user = $this->userRepository->findBy('email', $dto->email, 'tokens')->first();

        if (!$user->is_active) {
            throw new NotFoundHttpException('There is no confirmed account with such email');
        }

        if (!Hash::check($dto->password, $user->password)) {
            throw new InvalidArgumentException('Wrong password for specified email');
        }

        return $this->securityTokenService->generateToken($user);
    }

    public function refreshCode($dto)
    {
        if (!$dto instanceof SecurityRefreshCodeDto) {
            throw new InvalidArgumentException(get_class($this) . " refresh code method must receive a SecurityRefreshCodeDto");
        }

        $user = $this->userRepository->findById($dto->userId, 'codes');
        $code = $this->confirmationCodeService->refreshCode($user);

        $confirmation = [
            'confirmation' => $code,
            'recipient' => $user->email
        ];

        $email = EmailContentHelper::build($confirmation, EmailScope::CONFIRMATION);
        dispatch(new SendEmail($email));

        return [
            'user_id' => $user->user_id,
            'email' => $user->email
        ];
    }

    public function confirmAccount(DtoInterface $dto)
    {
        if (!$dto instanceof SecurityConfirmDto) {
            throw new InvalidArgumentException(get_class($this) . " confirm method must receive a SecurityConfirmDto");
        }

        /** @var User $user */
        $user = $this->userRepository->findById($dto->userId, 'codes');

        if ($user->is_active) {
            throw new InvalidArgumentException('User account is already active');
        }

        $code = $user->getLastValidCode();

        if (!$this->confirmationCodeService->isValid($code, $dto->code)) {
            throw new NotFoundHttpException('No valid codes for this user. Please refresh the confirmation code');
        }

        if (!Hash::check($dto->code, $code->code_text)) {
            throw new InvalidArgumentException('Invalid confirmation code');
        }

        $isConfirmed = $this->confirmationCodeService->confirmCode($code);

        if (!$isConfirmed) {
            throw new ServiceException('Error confirming code');
        }

        $user->is_active = true;
        $user->last_login_at = Carbon::now(3)->format('Y-m-d H:i:s');

        $this->userRepository->save($user);

        return $this->securityTokenService->generateToken($user);
    }
}
