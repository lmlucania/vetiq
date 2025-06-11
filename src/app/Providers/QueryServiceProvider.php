<?php

declare(strict_types=1);

namespace App\Providers;

use App\Application\QueryService\FavoriteQueryService;
use App\Application\QueryService\HospitalQueryService;
use App\Application\QueryService\MenuQueryService;
use App\Application\QueryService\ReviewQueryService;
use App\Infrastructure\QueryService\FavoriteQueryServiceInterface;
use App\Infrastructure\QueryService\HospitalQueryServiceInterface;
use App\Infrastructure\QueryService\MenuQueryServiceInterface;
use App\Infrastructure\QueryService\ReviewQueryServiceInterface;
use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ReviewQueryServiceInterface::class, ReviewQueryService::class);
        $this->app->bind(HospitalQueryServiceInterface::class, HospitalQueryService::class);
        $this->app->bind(MenuQueryServiceInterface::class, MenuQueryService::class);
        $this->app->bind(FavoriteQueryServiceInterface::class, FavoriteQueryService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
