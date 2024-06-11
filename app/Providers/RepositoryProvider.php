<?php

namespace App\Providers;

use App\Repositories\ConfirmationCodeRepositoryInterface;
use App\Repositories\Impl\ConfirmationCodeRepository;
use App\Repositories\Impl\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public array $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
        ConfirmationCodeRepositoryInterface::class => ConfirmationCodeRepository::class,
    ];
}
