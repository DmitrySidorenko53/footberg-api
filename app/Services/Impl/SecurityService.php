<?php

namespace App\Services\Impl;


use App\Enums\EmailScope;
use App\Exceptions\ServiceException;
use App\Helpers\EmailContentHelper;
use App\Http\Dto\Requests\DtoInterface;
use App\Http\Dto\Requests\Security\SecurityConfirmDto;
use App\Http\Dto\Requests\Security\SecurityRefreshCodeDto;
use App\Http\Dto\Requests\Security\SecurityRegisterDto;
use App\Jobs\SendEmail;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Services\ConfirmationCodeServiceInterface;
use App\Services\SecurityServiceInterface;
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

    public function __construct(UserRepositoryInterface $userRepository, ConfirmationCodeServiceInterface $confirmationCodeService)
    {
        $this->userRepository = $userRepository;
        $this->confirmationCodeService = $confirmationCodeService;
    }

    /**
     * @throws ServiceException
     */
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

        if (sizeof($code) == 0) {
            throw new ServiceException('Error creating code');
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
        return 'login method';// TODO: Implement login() method.
    }

    public function refreshCode($dto)
    {
        if (!$dto instanceof SecurityRefreshCodeDto) {
            throw new InvalidArgumentException(get_class($this) . " refresh code method must receive a SecurityRefreshCodeDto");
        }

        $user = $this->userRepository->findById($dto->userId, 'codes');
        $code = $this->confirmationCodeService->refreshCode($user);

        if (sizeof($code) == 0) {
            throw new ServiceException('Error refreshing code');
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

    public function confirmAccount(DtoInterface $dto)
    {
        if (!$dto instanceof SecurityConfirmDto) {
            throw new InvalidArgumentException(get_class($this) . " confirm method must receive a SecurityConfirmDto");
        }

        /** @var User $user */
        $user = $this->userRepository->findById($dto->userId, 'codes');

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
        $this->userRepository->save($user);

        return 'success';
        //todo login user
    }
}
