<?php

declare(strict_types=1);

namespace App\Domains\Hospital\Service;

use App\Application\Dto\HospitalDto;
use App\Domains\Hospital\Factory\HospitalFactory;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Models\HospitalModel;
use Illuminate\Support\Str;
use Ramsey\Collection\Collection;

class HospitalService
{
    public function __construct(
        private readonly HospitalFactory $hospitalFactory,
        private readonly HospitalRepositoryInterface $hospitalRepository
    ) {
    }

    public function getList():Collection
    {
        $hospitals = $this->hospitalRepository->getList();
        return $hospitals->map(static function (HospitalModel $hospital) {
            $entity = $this->hospitalFactory->toEntity($hospital);

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

    public function getByStaff():HospitalDto
    {
        $hospital       = auth()->user()->hospital;
        $hospitalEntity = $this->hospitalFactory->toEntity($hospital);

        return new HospitalDto(
            uuid: $hospitalEntity->getUuid(),
            name: $hospitalEntity->getName(),
            zipcode: $hospitalEntity->getZipcode(),
            address: $hospitalEntity->getAddress(),
            phone: $hospitalEntity->getPhone(),
            isPublished: $hospitalEntity->getIsPublished(),
        );
    }

    public function create(
        string $name,
        string $zipcode,
        string $address,
        string $phone,
        bool $isPublished
    ): void {
        $id = $this->hospitalRepository->generateId(HospitalModel::class);

        $entity = $this->hospitalFactory->createEntity(
            id:$id,
            uuid:(string)Str::uuid(),
            name:$name,
            zipcode: $zipcode,
            address: $address,
            phone: $phone,
            isPublished: $isPublished,
        );

        $this->hospitalRepository->create($entity);
    }

    public function update(
        string $name,
        string $zipcode,
        string $address,
        string $phone,
        bool $isPublished
    ): void {
        $hospital = auth()->user()->hospital;

        $entity = $this->hospitalFactory->createEntity(
            id:$hospital->id,
            uuid:$hospital->uuid,
            name:$name,
            zipcode: $zipcode,
            address: $address,
            phone: $phone,
            isPublished: $isPublished,
        );

        $this->hospitalRepository->update($entity);
    }
}
