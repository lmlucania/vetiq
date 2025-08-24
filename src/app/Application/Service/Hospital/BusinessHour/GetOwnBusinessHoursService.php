<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\BusinessHour;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\BusinessHour\Repositories\BusinessHourRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GetOwnBusinessHoursService
{
    public function __construct(
        private readonly BusinessHourRepositoryInterface $businessHourRepository,
        private readonly AuthActorService $authActorService,
    ) {
    }

    public function execute(): Collection
    {
        $hospitalId = $this->authActorService->getHospitalId();

        return $this->businessHourRepository->getListByHospitalId($hospitalId);
    }
}
