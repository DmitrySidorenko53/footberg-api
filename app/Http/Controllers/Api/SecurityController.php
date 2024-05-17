<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Dto\Requests\Security\SecurityLoginDto;
use App\Http\Dto\Requests\Security\SecurityRegistrationDto;
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

    public function registration(SecurityRegistrationDto $dto) {
        $response = $this->securityService->register($dto);
        return $response;
    }

    public function login(SecurityLoginDto $dto) {
        $response = $this->securityService->login($dto);
        return $response;
    }


}
