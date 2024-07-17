<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Dto\Requests\Profile\ChangePasswordDto;
use App\Http\Dto\Requests\Profile\ProfileFillDto;
use App\Http\Responses\ApiSuccessResponse;
use App\Interfaces\Service\ProfileServiceInterface;
use App\Interfaces\Service\SecurityPasswordServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private ProfileServiceInterface $profileService;
    private SecurityTokenServiceInterface $securityTokenService;
    private SecurityPasswordServiceInterface $securityPasswordService;

    public function __construct(
        ProfileServiceInterface       $profileService,
        SecurityTokenServiceInterface $securityTokenService,
        SecurityPasswordServiceInterface $securityPasswordService
    )
    {
        $this->profileService = $profileService;
        $this->securityTokenService = $securityTokenService;
        $this->securityPasswordService = $securityPasswordService;
    }

    public function fill(ProfileFillDto $dto)
    {
        $user = Auth::user();
        $data = $this->profileService->fillDetails($dto, $user);
        return new ApiSuccessResponse($data, 201, __('profile.success_filled'));
    }

    public function show($id = null)
    {
        $userId = $id ? (int)$id : Auth::id();
        $isMy = ($id && $id == Auth::id()) || $id === null;
        $data = $this->profileService->getDetailsByUserId($userId, $isMy);
        return new ApiSuccessResponse($data, 200);
    }

    public function logout()
    {
        $user = Auth::user();
        $this->securityTokenService->resetTokens($user);
        return new ApiSuccessResponse([],200, __('profile.logout'));
    }

    public function changePassword(ChangePasswordDto $dto)
    {
        $user = Auth::user();
        $data = $this->securityPasswordService->changePassword($dto, $user);
        return new ApiSuccessResponse($data, 200, __('profile.change_password'));
    }

    public function changeLanguage($lang)
    {
        $user = Auth::user();
        $this->profileService->changeLanguage($user, $lang);
        return new ApiSuccessResponse([], 200, __('profile.default_language'));
    }
}
