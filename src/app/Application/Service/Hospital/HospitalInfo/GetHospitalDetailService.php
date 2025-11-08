<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\HospitalInfo;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Models\Hospital;

class GetHospitalDetailService
{
    public function __construct(
        private readonly HospitalRepositoryInterface $hospitalRepository,
        private readonly AuthActorService $authActorService,
    ) {
    }

    public function execute(): Hospital
    {
        $hospitalId = $this->authActorService->getHospitalId();

        return $this->hospitalRepository->getByIdWithImage($hospitalId);
    }
}
