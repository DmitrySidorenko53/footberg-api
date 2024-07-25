<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Dto\Requests\Code\CodeDto;
use App\Http\Dto\Requests\Password\ChangePasswordDto;
use App\Http\Dto\Requests\Password\ForgotPasswordDto;
use App\Http\Dto\Requests\Password\PasswordRecoveryDto;
use App\Http\Responses\ApiResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Interfaces\Service\SecurityPasswordServiceInterface;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    private SecurityPasswordServiceInterface $securityPasswordService;

    /**
     * @param SecurityPasswordServiceInterface $securityPasswordService
     */
    public function __construct(SecurityPasswordServiceInterface $securityPasswordService)
    {
        $this->securityPasswordService = $securityPasswordService;
    }

    public function forgotPassword(ForgotPasswordDto $dto): ApiResponse
    {
        $data = $this->securityPasswordService->forgotPassword($dto);
        return new ApiSuccessResponse($data, 200, $data->getMessage());
    }

    public function resetPassword(CodeDto $dto): ApiResponse
    {
        $data = $this->securityPasswordService->resetPassword($dto);
        return new ApiSuccessResponse($data, 200, __('security.reset_password'));
    }

    public function recoveryPassword(PasswordRecoveryDto $dto): ApiResponse
    {
        $data = $this->securityPasswordService->recoveryPassword($dto);
        return new ApiSuccessResponse($data, 200, __('security.recovery_password'));
    }

    public function changePassword(ChangePasswordDto $dto): ApiResponse
    {
        $user = Auth::user();
        $data = $this->securityPasswordService->changePassword($dto, $user);
        return new ApiSuccessResponse($data, 200, __('profile.change_password'));
    }
}
