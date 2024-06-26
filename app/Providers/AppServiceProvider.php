<?php

namespace App\Providers;

use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Interfaces\Service\SecurityPasswordServiceInterface;
use App\Interfaces\Service\SecurityServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use App\Services\ConfirmationCodeService;
use App\Services\SecurityPasswordService;
use App\Services\SecurityService;
use App\Services\SecurityTokenService;
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
    ];
}
