<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Dto\Requests\Profile\ProfileFillDto;
use App\Http\Responses\ApiResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Interfaces\Service\ProfileServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private ProfileServiceInterface $profileService;

    public function __construct(
        ProfileServiceInterface       $profileService,
    )
    {
        $this->profileService = $profileService;
    }

    public function fill(ProfileFillDto $dto): ApiResponse
    {
        $user = Auth::user();
        $data = $this->profileService->fillDetails($dto, $user);
        return new ApiSuccessResponse($data, 201, __('profile.success_filled'));
    }

    public function show($id = null): ApiResponse
    {
        $userId = $id ? (int)$id : Auth::id();
        $isMy = ($id && $id == Auth::id()) || $id === null;
        $data = $this->profileService->getDetailsByUserId($userId, $isMy);
        return new ApiSuccessResponse($data, 200);
    }

    public function changeLanguage($lang): ApiResponse
    {
        $user = Auth::user();
        $this->profileService->changeLanguage($user, $lang);
        return new ApiSuccessResponse([], 200, __('profile.default_language'));
    }
}
