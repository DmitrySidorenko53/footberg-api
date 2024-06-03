<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    public function register() {

    }

    public function login() {

    }
}
