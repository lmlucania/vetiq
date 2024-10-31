<?php

declare(strict_types=1);

namespace App\Domains\Hospital\Service;

use App\Application\Dto\HospitalDto;
use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use Ramsey\Collection\Collection;

class HospitalService
{

    public function __construct(
        private readonly HospitalRepositoryInterface $hospitalRepository
    )
    {
    }

    public function getList():Collection
    {
        $entities = $this->hospitalRepository->getList();
        return $entities->map(static function(Hospital $entity) {
            return new HospitalDto(
                publicId: $entity->getPublicId(),
                name: $entity->getName(),
                zipcode: $entity->getZipcode(),
                address: $entity->getAddress(),
                phone: $entity->getPhone(),
                isPublished: $entity->getIsPublished()
            );
        });
    }

    public function getByStaff():HospitalDto
    {
        $staff  = auth()->user()->toEntity();
        $entity = $this->hospitalRepository->getById($staff->getHospitalId());
        return new HospitalDto(
            publicId: $entity->getPublicId(),
            name: $entity->getName(),
            zipcode: $entity->getZipcode(),
            address: $entity->getAddress(),
            phone: $entity->getPhone(),
            isPublished: $entity->getIsPublished()
        );
    }
}
