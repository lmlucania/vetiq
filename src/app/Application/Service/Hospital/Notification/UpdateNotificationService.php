<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Notification;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Notification\Repository\NotificationRepositoryInterface;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UpdateNotificationService
{
    public function __construct(
        private AuthActorService $authActorService,
        private NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    public function execute(
        int $id,
        string $title,
        string $detail,
        bool $isPublished,
        Carbon $publishedAt
    ) : bool {
        $notification = $this->notificationRepository->getByHospitalIdAndId(
            hospitalId: $this->authActorService->getHospitalId(),
            id: $id,
        );

        return $this->notificationRepository->update(
            id: $notification->id,
            title: $title,
            detail: $detail,
            isPublished: $isPublished,
            publishedAt: $publishedAt,
        );
    }
}
