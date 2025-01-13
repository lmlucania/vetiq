<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domains\Vet\Factory\VetFactory;
use App\Domains\Vet\Repository\VetRepositoryInterface;
use App\Models\VetModel;
use Illuminate\Support\Str;

class VetService
{
    public function __construct(
        private readonly VetFactory $vetFactory,
        private readonly AuthStaffService $authStaffService,
        private readonly VetRepositoryInterface $vetRepository,
    ) {
    }

    public function store(string $lastName, string $firstName, bool $acceptAppointment, string $remark)
    {
        $hospitalId = $this->authStaffService->getHospitalId();

        $id        = $this->vetRepository->generateId(VetModel::class);
        $vetEntity = $this->vetFactory->createEntityFromPrimitive(
            id:$id,
            uuid:(string)Str::uuid(),
            hospitalId: $hospitalId->getValue(),
            lastName: $lastName,
            firstName: $firstName,
            acceptAppointment: $acceptAppointment,
            remark: $remark,
        );

        return $this->vetRepository->create($vetEntity);
    }
}
