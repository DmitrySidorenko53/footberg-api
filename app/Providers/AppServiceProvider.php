<?php

namespace App\Providers;

use App\Repositories\ConfirmationCodeRepositoryInterface;
use App\Repositories\Impl\ConfirmationCodeRepository;
use App\Services\ConfirmationCodeServiceInterface;
use App\Services\Impl\ConfirmationCodeService;
use App\Services\Impl\SecurityService;
use App\Services\SecurityServiceInterface;
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
    ];
}
