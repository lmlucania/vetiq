<?php

namespace App\Application\Service\Hospital\Notification;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Notification\Repository\NotificationRepositoryInterface;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreateNotificationService
{

    public function __construct(
        private AuthActorService $authActorService,
        private NotificationRepositoryInterface $notificationRepository,
    )
    {
    }

    public function execute(  string $title,
                              string $detail,
                              bool $isPublished,
                              Carbon $publishedAt) : Notification
    {
        return $this->notificationRepository->create(
            uuid: (string)Str::uuid(),
            hospitalId: $this->authActorService->getHospitalId(),
            title: $title,
            detail: $detail,
            isPublished: $isPublished,
            publishedAt: $publishedAt,
        );
    }
}
