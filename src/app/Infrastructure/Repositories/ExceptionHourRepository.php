<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\ExceptionHour\Repositories\ExceptionHourRepositoryInterface;
use App\Models\ExceptionHour;
use Illuminate\Support\Collection;

class ExceptionHourRepository implements ExceptionHourRepositoryInterface
{
    public function getByHospitalIdAndId(int $hospitalId, int $id): ExceptionHour
    {
        return ExceptionHour::where('hospital_id', $hospitalId)->findOrFail($id);
    }

    public function getListByHospitalIdAndYearly(int $hospitalId, int $year): Collection
    {
        return ExceptionHour::where('hospital_id', $hospitalId)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get();
    }

    public function delete(int $id): bool
    {
        return ExceptionHour::findOrFail($id)->delete();
    }

    public function deleteByDateInHospital(int $hospitalId, string $date): int
    {
        return ExceptionHour::where('hospital_id', $hospitalId)
            ->whereDate('date', $date)
            ->delete();
    }

    public function createMany(array $rows): bool
    {
        return ExceptionHour::insert($rows);
    }
}
