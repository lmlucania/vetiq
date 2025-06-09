<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\Location\Enum\Prefecture;
use App\Models\Hospital;

class HospitalService
{
    public function __construct(
        private readonly HospitalRepositoryInterface $hospitalRepository,
        private readonly AuthActorService $authActorService,
    ) {
    }

    public function getByUuid(string $uuid)
    {
        return $this->hospitalRepository->getByUuid($uuid);
    }

    public function getOwn(): Hospital
    {
        $hospitalId = $this->authActorService->getHospitalId();

        return $this->hospitalRepository->getById($hospitalId);
    }

    public function updateOwn(
        string $name,
        string $phone,
        string $postCode,
        Prefecture $prefecture,
        string $address1,
        string $address2,
        bool $isPublished
    ): bool {
        $hospitalId = $this->authActorService->getHospitalId();

        return $this->hospitalRepository->update(
            id: $hospitalId,
            name: $name,
            phone: $phone,
            postCode: $postCode,
            prefecture: $prefecture,
            address1: $address1,
            address2: $address2,
            isPublished: $isPublished,
        );
    }
}
