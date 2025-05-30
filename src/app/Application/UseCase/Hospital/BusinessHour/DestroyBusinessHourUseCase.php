<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital\BusinessHour;

use App\Application\Service\BusinessHourService;

class DestroyBusinessHourUseCase
{
    public function __construct(
        private readonly BusinessHourService $businessHourService,
    ) {
    }

    public function execute(string $uuid): bool
    {
        return $this->businessHourService->delete($uuid);
    }
}
