<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Notification;

use App\Application\QueryService\NotificationQueryService;
use App\Application\Service\Auth\AuthActorService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetOwnNotificationsService
{
    public function __construct(
        private AuthActorService $authActorService,
        private NotificationQueryService $notificationQueryService,
    ) {
    }

    public function execute(int $page, int $perPage, string $keyword, array $sort, array $queryParam): LengthAwarePaginator
    {
        return $this->notificationQueryService->listByCriteria(
            hospitalId: $this->authActorService->getHospitalId(),
            page:$page,
            perPage: $perPage,
            keyword: $keyword,
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
