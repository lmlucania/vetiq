<?php

declare(strict_types=1);

namespace App\Application\Service\User\Appointment;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Appointment\Enum\AppointmentStatus;
use App\Domains\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\Menu\Repository\MenuRepositoryInterface;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use Carbon\Carbon;

class CreateAppointmentService
{
    public function __construct(
        private AuthActorService $authActorService,
        private AppointmentRepositoryInterface $appointmentRepository,
        private PetRepositoryInterface $petRepository,
        private HospitalRepositoryInterface $hospitalRepository,
        private MenuRepositoryInterface $menuRepository,
    ) {
    }

    public function execute(
        int $petId,
        int $hospitalId,
        int $menuId,
        ?int $vetId,
        Carbon $appointmentAt,
    ): bool {
        // fixme 予約時間などのバリデーションは一旦無視
        $pet = $this->petRepository->getByUserIdAndId(
            userId: $this->authActorService->getUserId(),
            id: $petId,
        );
        $menu = $this->menuRepository->getByHospitalIdAndId(
            hospitalId: $hospitalId,
            id: $menuId,
        );

        return $this->appointmentRepository->create(
            petId: $pet->id,
            hospitalId: $this->hospitalRepository->getById($hospitalId)->id,
            menuId: $menu->id,
            vetId: $vetId,
            appointmentAt: $appointmentAt,
            status: AppointmentStatus::Reserved,
            modifier: $this->authActorService->getUser(),
            hospitalMemo: null,
        );
    }
}
