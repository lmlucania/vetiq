<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domains\Appointment\Repositories\AppointmentStatusHistoryRepositoryInterface;
use App\Domains\BusinessHour\Repositories\BusinessHourRepositoryInterface;
use App\Domains\ExceptionHour\Repositories\ExceptionHourRepositoryInterface;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\HospitalViewHistory\Repositories\HospitalViewHistoryRepositoryInterface;
use App\Domains\Menu\Repository\MenuRepositoryInterface;
use App\Domains\Notification\Repository\NotificationRepositoryInterface;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use App\Domains\Review\Repository\ReviewRepositoryInterface;
use App\Domains\Tag\Repository\TagRepositoryInterface;
use App\Domains\User\Repository\UserProfileRepositoryInterface;
use App\Domains\User\Repository\UserRepositoryInterface;
use App\Domains\Vet\Repository\VetRepositoryInterface;
use App\Infrastructure\Repositories\AppointmentRepository;
use App\Infrastructure\Repositories\AppointmentStatusHistoryRepository;
use App\Infrastructure\Repositories\BusinessHourRepository;
use App\Infrastructure\Repositories\ExceptionHourRepository;
use App\Infrastructure\Repositories\HospitalRepository;
use App\Infrastructure\Repositories\HospitalViewHistoryRepository;
use App\Infrastructure\Repositories\MenuRepository;
use App\Infrastructure\Repositories\NotificationRepository;
use App\Infrastructure\Repositories\PetRepository;
use App\Infrastructure\Repositories\ReviewRepository;
use App\Infrastructure\Repositories\TagRepository;
use App\Infrastructure\Repositories\UserProfileRepository;
use App\Infrastructure\Repositories\UserRepository;
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
        $this->app->bind(PetRepositoryInterface::class, PetRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserProfileRepositoryInterface::class, UserProfileRepository::class);
        $this->app->bind(ReviewRepositoryInterface::class, ReviewRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(AppointmentRepositoryInterface::class, AppointmentRepository::class);
        $this->app->bind(AppointmentStatusHistoryRepositoryInterface::class, AppointmentStatusHistoryRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(HospitalViewHistoryRepositoryInterface::class, HospitalViewHistoryRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
