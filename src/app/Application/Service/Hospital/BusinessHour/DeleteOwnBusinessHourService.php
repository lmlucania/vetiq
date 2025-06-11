<?php

namespace App\Application\Service\Hospital\BusinessHour;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\BusinessHour\Repositories\BusinessHourRepositoryInterface;

class DeleteOwnBusinessHourService
{
    public function __construct(
        private readonly BusinessHourRepositoryInterface $businessHourRepository,
        private readonly AuthActorService $authActorService,
    ) {
    }

    public function execute(int $id): bool
    {
        $businessHour = $this->businessHourRepository->getByHospitalIdAndId(
            hospitalId: $this->authActorService->getHospitalId(),
            id: $id
        );

        return $this->businessHourRepository->delete($businessHour->id);
    }
}
