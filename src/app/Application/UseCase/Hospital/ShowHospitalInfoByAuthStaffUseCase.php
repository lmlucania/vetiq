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
        $hospitalEntity = $this->hospitalService->findByAuthStaff();

        return new HospitalDto(
            uuid: $hospitalEntity->getUuid(),
            name: $hospitalEntity->getName(),
            zipcode: $hospitalEntity->getZipcode(),
            address: $hospitalEntity->getAddress(),
            phone: $hospitalEntity->getPhone(),
            isPublished: $hospitalEntity->getIsPublished(),
        );
    }
}
