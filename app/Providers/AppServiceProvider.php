<?php

namespace App\Providers;

use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Interfaces\Service\EducationServiceInterface;
use App\Interfaces\Service\ProfileServiceInterface;
use App\Interfaces\Service\RoleServiceInterface;
use App\Interfaces\Service\SecurityPasswordServiceInterface;
use App\Interfaces\Service\SecurityServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use App\Interfaces\Service\TwoFactorServiceInterface;
use App\Services\ConfirmationCodeService;
use App\Services\EducationService;
use App\Services\ProfileService;
use App\Services\RoleService;
use App\Services\SecurityPasswordService;
use App\Services\SecurityService;
use App\Services\SecurityTokenService;
use App\Services\TwoFactorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }

    public array $bindings = [
        SecurityServiceInterface::class => SecurityService::class,
        ConfirmationCodeServiceInterface::class => ConfirmationCodeService::class,
        SecurityTokenServiceInterface::class => SecurityTokenService::class,
        SecurityPasswordServiceInterface::class => SecurityPasswordService::class,
        ProfileServiceInterface::class => ProfileService::class,
        RoleServiceInterface::class => RoleService::class,
        EducationServiceInterface::class => EducationService::class,
        TwoFactorServiceInterface::class => TwoFactorService::class,
    ];
}
