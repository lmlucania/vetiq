<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Notification;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Notification\Repository\NotificationRepositoryInterface;
use App\Models\Notification;
use Carbon\Carbon;

class CreateNotificationService
{
    public function __construct(
        private AuthActorService $authActorService,
        private NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    public function execute(
        string $title,
        string $detail,
        bool $isPublished,
        Carbon $publishedAt
    ) : Notification {
        return $this->notificationRepository->create(
            hospitalId: $this->authActorService->getHospitalId(),
            title: $title,
            detail: $detail,
            isPublished: $isPublished,
            publishedAt: $publishedAt,
        );
    }
}
