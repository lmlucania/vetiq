<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Vet;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Vet\Repository\VetRepositoryInterface;
use App\Models\Vet;

class GetOwnVetDetailService
{
    public function __construct(
        private VetRepositoryInterface $vetRepository,
        private AuthActorService $authActorService,
    ) {
    }

    public function execute(int $id): Vet
    {
        return $this->vetRepository->getByHospitalIdAndId(
            hospitalId: $this->authActorService->getHospitalId(),
            id: $id,
        );
    }
}
