<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Dto\Requests\Profile\ProfileFillDto;
use App\Http\Responses\ApiSuccessResponse;
use App\Interfaces\Service\ProfileServiceInterface;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private ProfileServiceInterface $profileService;

    public function __construct(ProfileServiceInterface $profileService)
    {
        $this->profileService = $profileService;
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
}
