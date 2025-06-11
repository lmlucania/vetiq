<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Vet;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Vet\Repository\VetRepositoryInterface;
use App\Models\Vet;
use Illuminate\Support\Str;

class CreateVetService
{
    public function __construct(
        private VetRepositoryInterface $vetRepository,
        private AuthActorService $authActorService,
    ) {
    }

    public function execute(
        string $lastName,
        string $firstName,
        bool $acceptAppointment,
        string $remark,
    ): Vet {
        return $this->vetRepository->create(
            uuid: (string)Str::uuid(),
            hospitalId: $this->authActorService->getHospitalId(),
            lastName: $lastName,
            firstName: $firstName,
            acceptAppointment: $acceptAppointment,
            remark: $remark,
        );
    }
}
