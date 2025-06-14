<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\ExceptionHour;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\ExceptionHour\Repositories\ExceptionHourRepositoryInterface;

class DeleteOwnExceptionHourService
{
    public function __construct(
        private AuthActorService $authActorService,
        private ExceptionHourRepositoryInterface $exceptionHourRepository,
    ) {
    }

    public function execute(int $id): bool
    {
        $exceptionHour = $this->exceptionHourRepository->getByHospitalIdAndId(
            hospitalId: $this->authActorService->getHospitalId(),
            id: $id,
        );

        return $this->exceptionHourRepository->delete($exceptionHour->id);
    }
}
