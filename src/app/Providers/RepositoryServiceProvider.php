<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\BusinessHour\Repositories\BusinessHourRepositoryInterface;
use App\Domains\ExceptionHour\Repositories\ExceptionHourRepositoryInterface;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\Menu\Repository\MenuRepositoryInterface;
use App\Domains\Vet\Repository\VetRepositoryInterface;
use App\Infrastructure\Repositories\BusinessHourRepository;
use App\Infrastructure\Repositories\ExceptionHourRepository;
use App\Infrastructure\Repositories\HospitalRepository;
use App\Infrastructure\Repositories\MenuRepository;
use App\Infrastructure\Repositories\VetRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(HospitalRepositoryInterface::class, HospitalRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(VetRepositoryInterface::class, VetRepository::class);
        $this->app->bind(BusinessHourRepositoryInterface::class, BusinessHourRepository::class);
        $this->app->bind(ExceptionHourRepositoryInterface::class, ExceptionHourRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
