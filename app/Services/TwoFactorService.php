<?php

namespace App\Services;

use App\Enums\ConfirmationCodeScopeEnum;
use App\Exceptions\InvalidIncomeTypeException;
use App\Http\Dto\Requests\TwoFA\TwoFactorCodeDto;
use App\Http\Dto\Response\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use App\Interfaces\Service\TwoFactorServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\UnauthorizedException;
use InvalidArgumentException;

class TwoFactorService implements TwoFactorServiceInterface
{
    private ConfirmationCodeServiceInterface $confirmationCodeService;
    private SecurityTokenServiceInterface $securityTokenService;
    private UserRepositoryInterface $userRepository;

    /**
     * @param ConfirmationCodeServiceInterface $confirmationCodeService
     * @param SecurityTokenServiceInterface $securityTokenService
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        ConfirmationCodeServiceInterface $confirmationCodeService,
        SecurityTokenServiceInterface    $securityTokenService,
        UserRepositoryInterface $userRepository
    )
    {
        $this->confirmationCodeService = $confirmationCodeService;
        $this->securityTokenService = $securityTokenService;
        $this->userRepository = $userRepository;
    }


    public function enableTwoFactorAuthentication(DtoInterface $dto, $user): AbstractDto
    {
        if (!$user instanceof User) {
            throw new InvalidIncomeTypeException(__METHOD__, User::class);
        }

        if (!$dto instanceof TwoFactorCodeDto) {
            throw new InvalidIncomeTypeException(__METHOD__, TwoFactorCodeDto::class);
        }

        if ($user->two_fa_enabled) {
            throw new InvalidArgumentException(__('two_factor.already_enabled'));
        }

        $code = $user->getLastValidCode(ConfirmationCodeScopeEnum::PHONE);

        $codeCandidate = $dto->code;

        $user->two_fa_enabled = true;
        $user->two_fa_enabled_at = now()->format('Y-m-d H:i:s');

        return DB::transaction(function () use ($user, $code, $codeCandidate) {
            $this->confirmationCodeService->tryConfirmCode($code, $codeCandidate);
            $this->userRepository->save($user);
            return $this->securityTokenService->generateToken($user);
        });
    }

    public function disableTwoFactorAuthentication($user): void
    {
        if (!$user instanceof User) {
            throw new InvalidIncomeTypeException(__METHOD__, User::class);
        }

        $user->two_fa_enabled = false;
        $user->two_fa_enabled_at = null;
        $user->security_phone_number = null;
        $this->userRepository->save($user);
    }

    //todo
    public function loginWithSmsCode(DtoInterface $dto): AbstractDto
    {
        if (!$dto instanceof TwoFactorCodeDto) {
            throw new InvalidIncomeTypeException(__METHOD__, TwoFactorCodeDto::class);
        }

        $userCandidate = Session::get('candidate');
        Session::forget('candidate');

        if (!$userCandidate) {
            throw new UnauthorizedException(__('two_factor.invalid_credentials'));
        }

        $code = $userCandidate->getLastValidCode(ConfirmationCodeScopeEnum::PHONE);

        $codeCandidate = $dto->code;

        $userCandidate->last_login_at = now();

        return DB::transaction(function () use ($userCandidate, $code, $codeCandidate) {
            $this->confirmationCodeService->tryConfirmCode($code, $codeCandidate);
            $this->userRepository->save($userCandidate);
            return $this->securityTokenService->generateToken($userCandidate);
        });
    }
}
