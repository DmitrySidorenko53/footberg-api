<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Dto\Requests\Security\SecurityConfirmDto;
use App\Http\Dto\Requests\Security\SecurityRefreshCodeDto;
use App\Http\Dto\Requests\Security\SecurityRegisterDto;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\SecurityServiceInterface;

class SecurityController extends Controller
{
    private SecurityServiceInterface $securityService;

    /**
     * @param SecurityServiceInterface $securityService
     */
    public function __construct(SecurityServiceInterface $securityService)
    {
        $this->securityService = $securityService;
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

    public function login()
    {

    }
}
