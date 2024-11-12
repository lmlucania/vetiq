<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital;

use App\Application\Dto\HospitalDto;
use App\Application\Service\HospitalService;

class ShowHospitalInfoByAuthStaffUseCase
{
    public function __construct(
        private readonly HospitalService $hospitalService
    ) {
    }

    public function show(): HospitalDto
    {
        return $this->hospitalService->findByAuthStaff();
    }
}
