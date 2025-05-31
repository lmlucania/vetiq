<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domains\ExceptionHour\Factory\ExceptionHourFactory;
use App\Domains\ExceptionHour\Repositories\ExceptionHourRepositoryInterface;
use App\Models\ExceptionHourModel;
use Illuminate\Support\Collection;

class ExceptionHourService
{
    public function __construct(
        private AuthStaffService $authStaffService,
        private ExceptionHourRepositoryInterface $exceptionHourRepositories,
        private ExceptionHourFactory $exceptionHourFactory,
    ) {
    }

    public function getListByYearly(int $year): Collection
    {
        $hospitalId = $this->authStaffService->getHospitalId();

        $models = $this->exceptionHourRepositories->getListByHospitalIdAndYearly($hospitalId, $year);

        return $models->map(
            fn (ExceptionHourModel $model) => $this->exceptionHourFactory->modelToEntity($model),
        );
    }
}
