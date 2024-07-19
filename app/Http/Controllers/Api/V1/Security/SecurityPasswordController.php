<?php

namespace App\Http\Controllers\Api\V1\Security;

use App\Http\Controllers\Controller;
use App\Http\Dto\Requests\Security\SecurityChangePasswordDto;
use App\Http\Dto\Requests\Security\SecurityCodeDto;
use App\Http\Dto\Requests\Security\SecurityForgotPasswordDto;
use App\Http\Dto\Requests\Security\SecurityPasswordRecoveryDto;
use App\Http\Responses\ApiResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Interfaces\Service\SecurityPasswordServiceInterface;
use Illuminate\Support\Facades\Auth;

class SecurityPasswordController extends Controller
{
    private SecurityPasswordServiceInterface $securityPasswordService;

    /**
     * @param SecurityPasswordServiceInterface $securityPasswordService
     */
    public function __construct(SecurityPasswordServiceInterface $securityPasswordService)
    {
        $this->securityPasswordService = $securityPasswordService;
    }


    public function forgotPassword(SecurityForgotPasswordDto $dto): ApiResponse
    {
        $data = $this->securityPasswordService->forgotPassword($dto);
        return new ApiSuccessResponse($data, 200, __('security.forgot_password'));
    }

    public function resetPassword(SecurityCodeDto $dto): ApiResponse
    {
        $data = $this->securityPasswordService->resetPassword($dto);
        return new ApiSuccessResponse($data, 200, __('security.reset_password'));
    }

    public function recoveryPassword(SecurityPasswordRecoveryDto $dto): ApiResponse
    {
        $data = $this->securityPasswordService->recoveryPassword($dto);
        return new ApiSuccessResponse($data, 200, __('security.recovery_password'));
    }

    public function changePassword(SecurityChangePasswordDto $dto): ApiResponse
    {
        $user = Auth::user();
        $data = $this->securityPasswordService->changePassword($dto, $user);
        return new ApiSuccessResponse($data, 200, __('profile.change_password'));
    }
}
