<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Dto\Requests\Security\SecurityCodeDto;
use App\Http\Dto\Requests\Security\SecurityCheckPhoneNumberDto;
use App\Http\Dto\Requests\Security\SecurityEnableTwoFactorDto;
use App\Http\Dto\Requests\Security\SecurityLoginDto;
use App\Http\Dto\Requests\Security\SecurityRefreshCodeDto;
use App\Http\Dto\Requests\Security\SecurityRefreshTokenDto;
use App\Http\Dto\Requests\Security\SecurityRegisterDto;
use App\Http\Responses\ApiResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Interfaces\Service\SecurityServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use App\Interfaces\Service\SmsServiceInterface;
use App\Interfaces\Service\TwoFactorServiceInterface;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    private SecurityServiceInterface $securityService;
    private SecurityTokenServiceInterface $securityTokenService;
    private TwoFactorServiceInterface $twoFactorService;
    private SmsServiceInterface $smsService;

    /**
     * @param SecurityServiceInterface $securityService
     * @param SecurityTokenServiceInterface $securityTokenService
     * @param TwoFactorServiceInterface $twoFactorService
     * @param SmsServiceInterface $smsService
     */
    public function __construct(
        SecurityServiceInterface      $securityService,
        SecurityTokenServiceInterface $securityTokenService,
        TwoFactorServiceInterface     $twoFactorService,
        SmsServiceInterface $smsService,
    )
    {
        $this->securityService = $securityService;
        $this->securityTokenService = $securityTokenService;
        $this->twoFactorService = $twoFactorService;
        $this->smsService = $smsService;
    }

    public function register(SecurityRegisterDto $dto): ApiResponse
    {
        $data = $this->securityService->register($dto);
        return new ApiSuccessResponse($data, 200, __('security.register'));
    }

    public function login(SecurityLoginDto $dto): ApiResponse
    {
        $data = $this->securityService->login($dto);
        return new ApiSuccessResponse($data, 200, __('security.login'));
    }

    public function confirm(SecurityCodeDto $dto): ApiResponse
    {
        $data = $this->securityService->confirmAccount($dto);
        return new ApiSuccessResponse($data, 200, __('code.confirmed'));
    }

    public function refreshConfirmationCode(SecurityRefreshCodeDto $dto): ApiResponse
    {
        $data = $this->securityService->refreshCode($dto);
        return new ApiSuccessResponse($data, 200, __('code.refreshed'));
    }


    public function refreshToken(SecurityRefreshTokenDto $dto): ApiResponse
    {
        $data = $this->securityTokenService->refresh($dto);
        return new ApiSuccessResponse($data, 200, __('token.refreshed'));
    }

    public function checkPhoneForTwoFactorAuthentication(SecurityCheckPhoneNumberDto $dto): ApiResponse
    {
        $user = Auth::user();
        $data = $this->smsService->sendCodeForTwoFactor($dto, $user);
        return new ApiSuccessResponse($data, 200, __('two_factor.checked_phone'));
    }

    public function enableTwoFactorAuthentication(SecurityEnableTwoFactorDto $dto): ApiResponse
    {
        $user = Auth::user();
        $data = $this->twoFactorService->enableTwoFactorAuthentication($dto, $user);
        return new ApiSuccessResponse($data, 200, __('two_factor.enabled'));
    }

    public function disableTwoFactorAuthentication(): ApiResponse
    {
        $user = Auth::user();
        $data = $this->twoFactorService->disableTwoFactorAuthentication($user);
        return new ApiSuccessResponse($data, 200, __('two_factor.disabled'));
    }
}
