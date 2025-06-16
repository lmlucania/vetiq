<?php

namespace App\Application\Service\Hospital\Notification;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Notification\Repository\NotificationRepositoryInterface;
use App\Models\Notification;
use Carbon\Carbon;

class GetOwnNotificationDetailService
{
    public function __construct(
        private AuthActorService $authActorService,
        private NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    public function execute(
        int $id,
    ) : Notification {

        return $this->notificationRepository->getByHospitalIdAndId(
            hospitalId: $this->authActorService->getHospitalId(),
            id: $id,
        );
    }
}
