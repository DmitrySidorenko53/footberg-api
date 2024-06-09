<?php

namespace App\Services\Impl;

use App\Exceptions\RepositoryException;
use App\Exceptions\ServiceException;
use App\Http\Dto\Requests\Security\SecurityRegisterDto;
use App\Mail\AppMail;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Services\SecurityServiceInterface;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;
use Random\RandomException;

class SecurityService implements SecurityServiceInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws ServiceException
     * @throws RandomException
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

        $confirmationCode = (string)random_int(100000, 999999);
        $user->confirmation_code = Hash::make($confirmationCode);

        $isSuccess = $this->userRepository->save($user);

        if (!$isSuccess) {
            throw new ServiceException('Error registering user');
        }

        //send code to email for confirm account
        Mail::to($user->email)->send(new AppMail(
            [
                'title' => 'Confirm your account',
                'confirmationCode' => $confirmationCode,
                'recipient' => $user->email,
            ],
            'confirmation-mail'));

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
