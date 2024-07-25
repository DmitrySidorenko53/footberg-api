<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Dto\Requests\Account\AccountLoginDto;
use App\Http\Dto\Requests\Account\AccountRegisterDto;
use App\Http\Dto\Requests\Account\RefreshTokenDto;
use App\Http\Dto\Requests\Code\CodeDto;
use App\Http\Dto\Response\Security\CodeDto as ResponseCodeDto;
use App\Http\Dto\Requests\Code\RefreshCodeDto;
use App\Http\Responses\ApiResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Interfaces\Service\SecurityServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    private SecurityServiceInterface $securityService;
    private SecurityTokenServiceInterface $securityTokenService;

    public function __construct(
        SecurityServiceInterface $securityService,
        SecurityTokenServiceInterface $securityTokenService
    )
    {
        $this->securityService = $securityService;
        $this->securityTokenService = $securityTokenService;
    }

    public function register(AccountRegisterDto $dto): ApiResponse
    {
        $data = $this->securityService->register($dto);
        return new ApiSuccessResponse($data, 200, __('security.register'));
    }

    public function login(AccountLoginDto $dto): ApiResponse
    {
        $data = $this->securityService->login($dto);
        $message = ($data instanceof ResponseCodeDto) ? $data->getMessage() : __('security.login');
        return new ApiSuccessResponse($data, 200, $message);
    }

    public function logout(): ApiResponse
    {
        $user = Auth::user();
        $this->securityTokenService->resetTokens($user);
        return new ApiSuccessResponse([],200, __('profile.logout'));
    }

    public function confirmAccount(CodeDto $dto): ApiResponse
    {
        $data = $this->securityService->confirmAccount($dto);
        return new ApiSuccessResponse($data, 200, __('code.confirmed'));
    }

    public function refreshConfirmationCode(RefreshCodeDto $dto): ApiResponse
    {
        $data = $this->securityService->refreshCode($dto);
        return new ApiSuccessResponse($data, 200, __('code.refreshed'));
    }

    public function refreshToken(RefreshTokenDto $dto): ApiResponse
    {
        $data = $this->securityTokenService->refresh($dto);
        return new ApiSuccessResponse($data, 200, __('token.refreshed'));
    }
}
