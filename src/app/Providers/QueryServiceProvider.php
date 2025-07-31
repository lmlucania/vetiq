<?php

declare(strict_types=1);

namespace App\Providers;

use App\Application\QueryService\AppointmentQueryService;
use App\Application\QueryService\FavoriteQueryService;
use App\Application\QueryService\HospitalQueryService;
use App\Application\QueryService\HospitalViewHistoryQueryService;
use App\Application\QueryService\MenuQueryService;
use App\Application\QueryService\NotificationQueryService;
use App\Application\QueryService\ReviewQueryService;
use App\Infrastructure\QueryService\AppointmentQueryServiceInterface;
use App\Infrastructure\QueryService\FavoriteQueryServiceInterface;
use App\Infrastructure\QueryService\HospitalQueryServiceInterface;
use App\Infrastructure\QueryService\HospitalViewHistoryQueryServiceInterface;
use App\Infrastructure\QueryService\MenuQueryServiceInterface;
use App\Infrastructure\QueryService\NotificationQueryServiceInterface;
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
        $this->app->bind(NotificationQueryServiceInterface::class, NotificationQueryService::class);
        $this->app->bind(AppointmentQueryServiceInterface::class, AppointmentQueryService::class);
        $this->app->bind(HospitalViewHistoryQueryServiceInterface::class, HospitalViewHistoryQueryService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
