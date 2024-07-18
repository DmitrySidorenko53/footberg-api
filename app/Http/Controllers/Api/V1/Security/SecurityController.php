<?php

namespace App\Http\Controllers\Api\V1\Security;

use App\Http\Controllers\Controller;
use App\Http\Dto\Requests\Security\SecurityCodeDto;
use App\Http\Dto\Requests\Security\SecurityLoginDto;
use App\Http\Dto\Requests\Security\SecurityRefreshCodeDto;
use App\Http\Dto\Requests\Security\SecurityRefreshTokenDto;
use App\Http\Dto\Requests\Security\SecurityRegisterDto;
use App\Http\Responses\ApiSuccessResponse;
use App\Interfaces\Service\SecurityServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;

class SecurityController extends Controller
{
    private SecurityServiceInterface $securityService;
    private SecurityTokenServiceInterface $securityTokenService;

    /**
     * @param SecurityServiceInterface $securityService
     * @param SecurityTokenServiceInterface $securityTokenService
     */
    public function __construct(
        SecurityServiceInterface         $securityService,
        SecurityTokenServiceInterface    $securityTokenService,
    )
    {
        $this->securityService = $securityService;
        $this->securityTokenService = $securityTokenService;
    }

    public function register(SecurityRegisterDto $dto)
    {
        $data = $this->securityService->register($dto);
        return new ApiSuccessResponse($data, 200, __('security.register'));
    }

    public function confirm(SecurityCodeDto $dto)
    {
        $data = $this->securityService->confirmAccount($dto);
        return new ApiSuccessResponse($data, 200, __('code.confirmed'));
    }

    public function refreshConfirmationCode(SecurityRefreshCodeDto $dto)
    {
        $data = $this->securityService->refreshCode($dto);
        return new ApiSuccessResponse($data, 200, __('code.refreshed'));
    }

    public function login(SecurityLoginDto $dto)
    {
        $data = $this->securityService->login($dto);
        return new ApiSuccessResponse($data, 200, __('security.login'));
    }

    public function refreshToken(SecurityRefreshTokenDto $dto)
    {
        $data = $this->securityTokenService->refresh($dto);
        return new ApiSuccessResponse($data, 200, __('token.refreshed'));
    }
}
