<?php

declare(strict_types=1);

namespace App\Application\Service\User\Appointment;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Appointment\Factory\AppointmentFactory;
use App\Domains\Appointment\Repositories\AppointmentStatusHistoryRepositoryInterface;
use App\Models\AppointmentStatusHistory;

class CancelAppointmentService
{
    public function __construct(
        private AuthActorService $authActorService,
        private AppointmentStatusHistoryRepositoryInterface $appointmentStatusHistoryRepository,
        private AppointmentFactory $appointmentFactory,
    ) {
    }

    public function execute(int $appointmentId): AppointmentStatusHistory
    {
        $latestStatus = $this->appointmentStatusHistoryRepository->getLatestByAppointmentId(
            userId: $this->authActorService->getUserId(),
            appointmentId: $appointmentId,
        );

        $appointmentEntity = $this->appointmentFactory->modelToEntity(
            appointment: $latestStatus->appointment,
            statusHistory: $latestStatus,
        );

        $canceledEntity = $appointmentEntity->cancel(
            modifier: $this->authActorService->getUser(),
            hospitalMemo: null,
        );
        return $this->appointmentStatusHistoryRepository->create(
            appointmentEntity: $canceledEntity,
            modifier: $this->authActorService->getUser(),
        );
    }
}
