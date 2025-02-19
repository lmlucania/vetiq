<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Dto\HospitalDto;
use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\Factory\HospitalFactory;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Models\HospitalModel;
use Illuminate\Support\Str;
use Ramsey\Collection\Collection;

class HospitalService
{
    public function __construct(
        private readonly HospitalFactory $hospitalFactory,
        private readonly HospitalRepositoryInterface $hospitalRepository,
        private readonly AuthStaffService $authStaffService,
    ) {
    }

    public function getList():Collection
    {
        $hospitals = $this->hospitalRepository->getList();
        return $hospitals->map(static function (HospitalModel $hospital) {
            $entity = $this->hospitalFactory->modelToEntity($hospital);

            return new HospitalDto(
                uuid: $entity->getUuid(),
                name: $entity->getName(),
                zipcode: $entity->getZipcode(),
                address: $entity->getAddress(),
                phone: $entity->getPhone(),
                isPublished: $entity->getIsPublished(),
            );
        });
    }

    public function findByAuthStaff():Hospital
    {
        $hospitalId = $this->authStaffService->getHospitalId();

        $hospitalModel  = $this->hospitalRepository->getById($hospitalId);
        $hospitalEntity = $this->hospitalFactory->modelToEntity($hospitalModel);

        return $hospitalEntity;
    }

    public function create(
        string $name,
        string $zipcode,
        string $address,
        string $phone,
        bool $isPublished
    ): bool {
        $id = $this->hospitalRepository->generateId(HospitalModel::class);

        $hospitalEntity = $this->hospitalFactory->createEntityFromPrimitive(
            id:$id,
            uuid:(string)Str::uuid(),
            name:$name,
            zipcode: $zipcode,
            address: $address,
            phone: $phone,
            isPublished: $isPublished,
        );

        return $this->hospitalRepository->create($hospitalEntity);
    }

    public function updateByAuthStaff(
        string $name,
        string $zipcode,
        string $address,
        string $phone,
        bool $isPublished
    ): bool {
        $hospitalEntity = $this->findByAuthStaff();

        $hospitalEntity = $hospitalEntity->update(
            name:$name,
            zipcode: $zipcode,
            address: $address,
            phone: $phone,
            isPublished: $isPublished,
        );

        return $this->hospitalRepository->update($hospitalEntity);
    }
}
