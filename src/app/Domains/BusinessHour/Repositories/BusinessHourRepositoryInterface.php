<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\Repositories;

use App\Models\BusinessHour;
use Illuminate\Database\Eloquent\Collection;

interface BusinessHourRepositoryInterface
{
    public function getByHospitalIdAndId(int $hospitalId, int $id): BusinessHour;

    public function getListByHospitalId(int $hospitalId): Collection;

    public function delete(int $id): bool;

    public function deleteByDayOfWeekInHospital(int $hospitalId, int $dayOfWeek): int;

    public function createMany(array $rows): int;
}
