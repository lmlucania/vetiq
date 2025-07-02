<?php

declare(strict_types=1);

namespace App\Application\Service\User\Appointment;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Models\Appointment;

class GetAppointmentDetailService
{
    public function __construct(
        private AuthActorService $authActorService,
        private AppointmentRepositoryInterface $appointmentRepository,
    ) {
    }

    public function execute(int $appointmentId): Appointment
    {
        return $this->appointmentRepository->getWithStatusHistoriesByUserIdAndId(
            userId: $this->authActorService->getUserId(),
            id: $appointmentId,
        );
    }
}
