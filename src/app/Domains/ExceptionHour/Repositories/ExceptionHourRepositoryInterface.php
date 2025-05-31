<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\Repositories;

use App\Domains\ExceptionHour\Entity\ExceptionHour;
use App\Domains\ExceptionHour\ValueObjects\DeletableExceptionHourId;
use App\Domains\ExceptionHour\ValueObjects\ExceptionHourUuid;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Models\ExceptionHourModel;
use Illuminate\Support\Collection;

interface ExceptionHourRepositoryInterface
{
    public function generateId(string $modelClass): int;

    public function getByUuid(ExceptionHourUuid $uuid): ExceptionHourModel;

    public function getListByHospitalIdAndYearly(HospitalId $hospitalId, int $year): Collection;

    public function create(ExceptionHour $ExceptionHourEntity): bool;

    public function update(ExceptionHour $ExceptionHourEntity): bool;

    public function delete(DeletableExceptionHourId $id): bool;
}
