<?php

declare(strict_types=1);

namespace App\Domains\Vet\DomainService;

use App\Domains\Vet\Repository\VetRepositoryInterface;

class VetDomainService
{
    public function __construct(
        private VetRepositoryInterface $vetRepository,
    ) {
    }

    public function canDelete(int $hospitalId): bool
    {
        return $this->vetRepository->countByHospitalId($hospitalId) >= 2;
    }
}
