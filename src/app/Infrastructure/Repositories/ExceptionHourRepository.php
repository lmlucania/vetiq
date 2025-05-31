<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\ExceptionHour\Entity\ExceptionHour;
use App\Domains\ExceptionHour\Repositories\ExceptionHourRepositoryInterface;
use App\Domains\ExceptionHour\ValueObjects\DeletableExceptionHourId;
use App\Domains\ExceptionHour\ValueObjects\ExceptionHourUuid;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Exceptions\NotFoundException;
use App\Infrastructure\Repositories\Traits\GenerationId;
use App\Models\ExceptionHourModel;
use Illuminate\Support\Collection;

class ExceptionHourRepository implements ExceptionHourRepositoryInterface
{
    use GenerationId;

    public function getByUuid(ExceptionHourUuid $uuid): ExceptionHourModel
    {
        $model = ExceptionHourModel::firstWhere('uuid', $uuid->getValue());
        if ($model == null) {
            throw new NotFoundException();
        }

        return $model;
    }

    public function getListByHospitalIdAndYearly(HospitalId $hospitalId, int $year): Collection
    {
        return ExceptionHourModel::where('hospital_id', $hospitalId->getValue())
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get();
    }

    public function create(ExceptionHour $ExceptionHourEntity): bool
    {
        // TODO: Implement create() method.
    }

    public function update(ExceptionHour $ExceptionHourEntity): bool
    {
        // TODO: Implement update() method.
    }

    public function delete(DeletableExceptionHourId $id): bool
    {
        // TODO: Implement delete() method.
    }
}
