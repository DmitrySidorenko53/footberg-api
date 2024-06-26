<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Dto\Requests\Security\SecurityConfirmDto;
use App\Http\Dto\Requests\Security\SecurityForgotPasswordDto;
use App\Http\Dto\Requests\Security\SecurityLoginDto;
use App\Http\Dto\Requests\Security\SecurityPasswordRecoveryDto;
use App\Http\Dto\Requests\Security\SecurityPasswordResetDto;
use App\Http\Dto\Requests\Security\SecurityRefreshCodeDto;
use App\Http\Dto\Requests\Security\SecurityRefreshTokenDto;
use App\Http\Dto\Requests\Security\SecurityRegisterDto;
use App\Http\Responses\ApiSuccessResponse;
use App\Interfaces\Service\SecurityPasswordServiceInterface;
use App\Interfaces\Service\SecurityServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;

class SecurityController extends Controller
{
    private SecurityServiceInterface $securityService;
    private SecurityTokenServiceInterface $securityTokenService;
    private SecurityPasswordServiceInterface $securityPasswordService;

    /**
     * @param SecurityServiceInterface $securityService
     * @param SecurityTokenServiceInterface $securityTokenService
     * @param SecurityPasswordServiceInterface $securityPasswordService
     */
    public function __construct(
        SecurityServiceInterface         $securityService,
        SecurityTokenServiceInterface    $securityTokenService,
        SecurityPasswordServiceInterface $securityPasswordService
    )
    {
        $this->securityService = $securityService;
        $this->securityTokenService = $securityTokenService;
        $this->securityPasswordService = $securityPasswordService;
    }

    public function register(SecurityRegisterDto $dto)
    {
        $data = $this->securityService->register($dto);
        return new ApiSuccessResponse($data, 201, 'Successfully created user account. Please confirm your account by email');
    }

    public function confirm(SecurityConfirmDto $dto)
    {
        $data = $this->securityService->confirmAccount($dto);
        return new ApiSuccessResponse($data, 200, 'Successfully confirmed user account');
    }

    public function refreshConfirmationCode(SecurityRefreshCodeDto $dto)
    {
        $data = $this->securityService->refreshCode($dto);
        return new ApiSuccessResponse($data, 200, 'Successfully refreshed confirmation code');
    }

    public function login(SecurityLoginDto $dto)
    {
        $data = $this->securityService->login($dto);
        return new ApiSuccessResponse($data, 200, 'Successfully logged in');
    }

    public function refreshToken(SecurityRefreshTokenDto $dto)
    {
        $data = $this->securityTokenService->refresh($dto);
        return new ApiSuccessResponse($data, 200, 'Successfully refreshed token');
    }

    public function forgotPassword(SecurityForgotPasswordDto $dto)
    {
        $data = $this->securityPasswordService->forgotPassword($dto);
        return new ApiSuccessResponse($data, 200, 'Successfully sent password reset email');
    }

    public function resetPassword(SecurityPasswordResetDto $dto)
    {
        $data = $this->securityPasswordService->resetPassword($dto);
        return new ApiSuccessResponse($data, 200, 'Successfully reset password');
    }

    public function recoveryPassword(SecurityPasswordRecoveryDto $dto)
    {
        $data = $this->securityPasswordService->recoveryPassword($dto);
        return new ApiSuccessResponse($data, 200, 'Successfully recovery password');
    }
}
