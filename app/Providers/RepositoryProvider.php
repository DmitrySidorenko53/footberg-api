<?php

namespace App\Providers;

use App\Interfaces\Repository\AccountDetailsRepositoryInterface;
use App\Interfaces\Repository\ConfirmationCodeRepositoryInterface;
use App\Interfaces\Repository\EducationalDegreeRepositoryInterface;
use App\Interfaces\Repository\EducationalInstitutionRepositoryInterface;
use App\Interfaces\Repository\RoleUserRepositoryInterface;
use App\Interfaces\Repository\SecurityTokenRepositoryInterface;
use App\Interfaces\Repository\UserEducationRepositoryInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Repositories\AccountDetailsRepository;
use App\Repositories\ConfirmationCodeRepository;
use App\Repositories\EducationalDegreeRepository;
use App\Repositories\EducationalInstitutionRepository;
use App\Repositories\RoleUserRepository;
use App\Repositories\SecurityTokenRepository;
use App\Repositories\UserEducationRepository;
use App\Repositories\UserRepository;
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
        SecurityTokenRepositoryInterface::class => SecurityTokenRepository::class,
        RoleUserRepositoryInterface::class => RoleUserRepository::class,
        EducationalDegreeRepositoryInterface::class => EducationalDegreeRepository::class,
        EducationalInstitutionRepositoryInterface::class => EducationalInstitutionRepository::class,
        AccountDetailsRepositoryInterface::class => AccountDetailsRepository::class,
        UserEducationRepositoryInterface::class => UserEducationRepository::class,
    ];
}
