<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\ExceptionHour\Repositories\ExceptionHourRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Infrastructure\Repositories\Traits\GenerationId;
use App\Models\ExceptionHour;
use Illuminate\Support\Collection;

class ExceptionHourRepository implements ExceptionHourRepositoryInterface
{
    use GenerationId;

    public function getByUuid(ExceptionHourUuid $uuid): ExceptionHour
    {
        $model = ExceptionHour::firstWhere('uuid', $uuid->getValue());
        if ($model == null) {
            throw new NotFoundException();
        }

        return $model;
    }

    public function getListByHospitalIdAndYearly(int $hospitalId, int $year): Collection
    {
        return ExceptionHour::where('hospital_id', $hospitalId)
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
