<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital;

use App\Application\Service\HospitalService;

class UpdateHospitalInfoByAuthStaffUseCase
{
    public function __construct(
        private readonly HospitalService $hospitalService
    ) {
    }

    public function update(
        string $name,
        string $zipcode,
        string $address,
        string $phone,
        bool $isPublished
    ): bool {
        return $this->hospitalService->updateByAuthStaff(
            $name,
            $zipcode,
            $address,
            $phone,
            $isPublished,
        );
    }
}
