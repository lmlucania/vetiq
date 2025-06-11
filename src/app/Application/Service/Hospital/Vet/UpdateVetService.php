<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Vet;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Vet\Repository\VetRepositoryInterface;

class UpdateVetService
{
    public function __construct(
        private VetRepositoryInterface $vetRepository,
        private AuthActorService $authActorService,
    ) {
    }

    public function execute(
        int $id,
        string $lastName,
        string $firstName,
        bool $acceptAppointment,
        string $remark,
    ): bool {
        $vet = $this->vetRepository->getByHospitalIdAndId(
            hospitalId: $this->authActorService->getHospitalId(),
            id: $id,
        );

        return $this->vetRepository->update(
            id: $vet->id,
            hospitalId: $this->authActorService->getHospitalId(),
            lastName: $lastName,
            firstName: $firstName,
            acceptAppointment: $acceptAppointment,
            remark: $remark,
        );
    }
}
