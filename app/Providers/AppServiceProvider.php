<?php

namespace App\Providers;

use App\Services\Security\Impl\SecurityService;
use App\Services\Security\SecurityServiceInterface;
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
        SecurityServiceInterface::class => SecurityService::class
    ];
}
