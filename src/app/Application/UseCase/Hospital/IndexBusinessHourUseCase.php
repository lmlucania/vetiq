<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital;

use App\Application\Dto\Response\BusinessHourCollectionDto;
use App\Application\Service\BusinessHourService;
use App\Domains\BusinessHour\Entity\BusinessHour;
use App\Domains\BusinessHour\Factory\BusinessHourFactory;

class IndexBusinessHourUseCase
{
    public function __construct(
        private readonly BusinessHourService $businessHourService,
        private readonly BusinessHourFactory $businessHourFactory
    ) {
    }

    public function execute():BusinessHourCollectionDto
    {
        $businessHourEntities = $this->businessHourService->getList();

        $dtoCollection = $businessHourEntities->map(function (BusinessHour $entity) {
            return $this->businessHourFactory->entityToDto($entity);
        });

        return new BusinessHourCollectionDto($dtoCollection);
    }
}
