<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\Repositories;

use App\Models\ExceptionHour;
use Illuminate\Support\Collection;

interface ExceptionHourRepositoryInterface
{
    public function getByHospitalIdAndId(int $hospitalId, int $id): ExceptionHour;

    public function getListByHospitalIdAndYearly(int $hospitalId, int $year): Collection;

    public function delete(int $id): bool;

    public function deleteByDateInHospital(int $hospitalId, string $date): int;

    public function createMany(array $rows): bool;
}
