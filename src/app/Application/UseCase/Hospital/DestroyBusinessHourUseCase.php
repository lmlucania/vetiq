<?php

namespace App\Application\UseCase\Hospital;

use App\Application\Service\BusinessHourService;

class DestroyBusinessHourUseCase
{
    public function __construct(
        private readonly BusinessHourService $businessHourService,
    )
    {
    }

    public function execute(string $uuid): bool
    {
        return $this->businessHourService->delete($uuid);
    }
}
