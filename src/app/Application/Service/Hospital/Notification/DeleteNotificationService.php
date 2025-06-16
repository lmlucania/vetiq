<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Notification;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Notification\Repository\NotificationRepositoryInterface;

class DeleteNotificationService
{
    public function __construct(
        private AuthActorService $authActorService,
        private NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    public function execute(int $id) : bool
    {
        $notification = $this->notificationRepository->getByHospitalIdAndId(
            hospitalId: $this->authActorService->getHospitalId(),
            id: $id,
        );

        return $this->notificationRepository->delete($notification->id);
    }
}
