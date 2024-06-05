<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Dto\Requests\Security\SecurityRegisterDto;
use App\Http\Responses\ApiFailResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\Security\SecurityServiceInterface;

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
        return new ApiSuccessResponse([], 200, 'Successfully register user');
    }

    public function login()
    {

    }
}
