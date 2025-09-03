<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\HospitalInfo;

use App\Application\Dto\Request\HospitalImageDto;
use App\Application\Service\Auth\AuthActorService;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\Location\Enum\Prefecture;

class UpdateHospitalInfoService
{
    public function __construct(
        private readonly HospitalRepositoryInterface $hospitalRepository,
        private readonly AuthActorService $authActorService,
        private readonly SyncHospitalImageService $syncHospitalImageService,
    ) {
    }

    /**
     * @param string $name
     * @param string $phone
     * @param string $postCode
     * @param Prefecture $prefecture
     * @param string $address1
     * @param string|null $address2
     * @param bool $isPublished
     * @param HospitalImageDto[] $dtos
     * @return bool
     */
    public function execute(
        string $name,
        string $phone,
        string $postCode,
        Prefecture $prefecture,
        string $address1,
        ?string $address2,
        bool $isPublished,
        array $dtos,
    ): bool {
        $this->syncHospitalImageService->execute(
            dtos: $dtos,
        );

        return $this->hospitalRepository->update(
            id: $this->authActorService->getHospitalId(),
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
