<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\ExceptionHour;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\ExceptionHour\Repositories\ExceptionHourRepositoryInterface;
use Illuminate\Support\Collection;

class GetOwnExceptionHoursYearlyService
{
    public function __construct(
        private AuthActorService $authActorService,
        private ExceptionHourRepositoryInterface $exceptionHourRepository,
    ) {
    }

    public function execute(int $year): Collection
    {
        return $this->exceptionHourRepository->getListByHospitalIdAndYearly(
            hospitalId: $this->authActorService->getHospitalId(),
            year: $year,
        );
    }
}
