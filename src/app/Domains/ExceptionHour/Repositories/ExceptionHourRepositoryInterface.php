<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\Repositories;

use Illuminate\Support\Collection;

interface ExceptionHourRepositoryInterface
{
    public function getListByHospitalIdAndYearly(int $hospitalId, int $year): Collection;

    public function delete(int $id): bool;

    public function deleteByDateInHospital(int $hospitalId, string $date): int;

    public function createMany(array $rows): int;
}
