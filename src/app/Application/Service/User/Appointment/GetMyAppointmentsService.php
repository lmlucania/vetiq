<?php

declare(strict_types=1);

namespace App\Application\Service\User\Appointment;

use App\Application\Service\Auth\AuthActorService;
use App\Infrastructure\QueryService\AppointmentQueryServiceInterface;

class GetMyAppointmentsService
{
    public function __construct(
        private AuthActorService $authActorService,
        private AppointmentQueryServiceInterface $appointmentQueryService,
    ) {
    }

    public function execute(int $page, int $perPage, array $sort, array $queryParam)
    {
        return $this->appointmentQueryService->listByUserId(
            userId: $this->authActorService->getUserId(),
            page:$page,
            perPage: $perPage,
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
