<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Domains\BusinessHour\Enum\DayOfWeek;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Models\BusinessHourModel;
use Illuminate\Database\Eloquent\Collection;

final class BusinessHourQueryService
{
    /**
     * 指定した曜日において、指定された時間帯以外のデータを取得する
     * @param HospitalId $hospitalId
     * @param DayOfWeek $dayOfWeek
     * @param array $timePeriods
     * @return Collection
     */
    public function getByDayOfWeekExcludingTimePeriods(HospitalId $hospitalId, DayOfWeek $dayOfWeek, array $timePeriods): Collection
    {
        return BusinessHourModel::where('day_of_week', $dayOfWeek)
            ->where('hospital_id', $hospitalId->getValue())
            ->whereNotIn('time_period', $timePeriods)
            ->get();
    }
}
