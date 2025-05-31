<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital\ExceptionHour;

use App\Application\Dto\Response\ExceptionHourCollectionDto;
use App\Application\Service\ExceptionHourService;
use App\Domains\ExceptionHour\Entity\ExceptionHour;
use App\Domains\ExceptionHour\Factory\ExceptionHourFactory;

class IndexExceptionHourUseCase
{
    public function __construct(
        private ExceptionHourService $exceptionHourService,
        private ExceptionHourFactory $exceptionHourFactory,
    ) {
    }

    public function execute(int $year): ExceptionHourCollectionDto
    {
        $exceptionHourEntities = $this->exceptionHourService->getListByYearly($year);

        $dtoCollection = $exceptionHourEntities->map(
            fn (ExceptionHour $entity) => $this->exceptionHourFactory->entityToDto($entity),
        );

        return new ExceptionHourCollectionDto($dtoCollection);
    }
}
