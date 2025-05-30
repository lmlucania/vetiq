<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital\BusinessHour;

use App\Application\Dto\Request\BusinessHourDto;
use App\Application\Service\BusinessHourService;

class StoreBusinessHourUseCase
{
    public function __construct(
        private readonly BusinessHourService $businessHourService,
    ) {
    }

    public function execute(int $dayOfWeek, array $periods):bool
    {
        $dto = BusinessHourDto::fromPrimitive(
            dayOfWeek: $dayOfWeek,
            periods: $periods,
        );

        return $this->businessHourService->sync($dto);
    }
}
