<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\BusinessHour\Repositories\BusinessHourRepositoryInterface;
use App\Domains\Schedule\Enum\DayOfWeek;
use App\Models\BusinessHour;
use Illuminate\Database\Eloquent\Collection;

class BusinessHourRepository implements BusinessHourRepositoryInterface
{
    public function getByHospitalIdAndId(int $hospitalId, int $id): BusinessHour
    {
        return BusinessHour::where('hospital_id', $hospitalId)->findOrFail($id);
    }

    public function getListByHospitalId(int $hospitalId): Collection
    {
        return BusinessHour::where('hospital_id', $hospitalId)
            ->orderBy('day_of_week')
            ->orderBy('time_period')
            ->get();
    }

    public function delete(int $id): bool
    {
        $model = BusinessHour::findOrFail($id);

        return $model->delete();
    }

    public function deleteByDayOfWeekInHospital(int $hospitalId, DayOfWeek $dayOfWeek): int
    {
        return BusinessHour::where('hospital_id', $hospitalId)
            ->where('day_of_week', $dayOfWeek->value)
            ->delete();
    }

    public function createMany(array $rows): bool
    {
        return BusinessHour::insert($rows);
    }
}
