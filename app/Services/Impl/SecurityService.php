<?php

namespace App\Services\Impl;


use App\Exceptions\ServiceException;
use App\Http\Dto\Requests\Security\SecurityRegisterDto;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Services\ConfirmationCodeServiceInterface;
use App\Services\SecurityServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Random\RandomException;

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

        $isSuccess = $this->userRepository->save($user);

        if (!$isSuccess) {
            throw new ServiceException('Error registering user');
        }

        $code = $this->confirmationCodeService->createConfirmationCode($user);
        $this->confirmationCodeService->sendEmail($code['code'], $user->email);

        return [
            'user_id' => $user->user_id,
            'email' => $user->email
        ];
    }

    public function login($dto)
    {
        return 'login method';// TODO: Implement login() method.
    }
}
