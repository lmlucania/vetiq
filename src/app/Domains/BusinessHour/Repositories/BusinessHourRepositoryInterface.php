<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\Repositories;

use App\Domains\BusinessHour\Entity\BusinessHour;
use App\Domains\BusinessHour\Enum\DayOfWeek;
use App\Domains\BusinessHour\Enum\TimePeriod;
use App\Domains\BusinessHour\ValueObjects\BusinessHourUuid;
use App\Domains\BusinessHour\ValueObjects\DeletableBusinessHourId;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Models\BusinessHourModel;
use Illuminate\Database\Eloquent\Collection;

interface BusinessHourRepositoryInterface
{
    public function generateId(string $modelClass): int;

    public function getByUuid(BusinessHourUuid $uuid): BusinessHourModel;

    public function findBySchedule(
        HospitalId $hospitalId,
        DayOfWeek $dayOfWeek,
        TimePeriod $timePeriod
    ): ?BusinessHourModel;

    public function getListByHospitalId(HospitalId $hospitalId): Collection;

    public function create(BusinessHour $businessHourEntity): bool;

    public function update(BusinessHour $businessHourEntity): bool;

    public function delete(DeletableBusinessHourId $id): bool;
}
