<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\BusinessHour\Entity\BusinessHour;
use App\Domains\BusinessHour\Enum\DayOfWeek;
use App\Domains\BusinessHour\Enum\TimePeriod;
use App\Domains\BusinessHour\Factory\BusinessHourFactory;
use App\Domains\BusinessHour\Repositories\BusinessHourRepositoryInterface;
use App\Domains\BusinessHour\ValueObjects\BusinessHourUuid;
use App\Domains\BusinessHour\ValueObjects\DeletableBusinessHourId;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Exceptions\NotFoundException;
use App\Infrastructure\Repositories\Traits\GenerationId;
use App\Models\BusinessHourModel;
use Illuminate\Database\Eloquent\Collection;

class BusinessHourRepository implements BusinessHourRepositoryInterface
{
    use GenerationId;

    public function __construct(
        private readonly BusinessHourFactory $businessHourFactory
    ) {
    }

    public function getByUuid(BusinessHourUuid $uuid): BusinessHourModel
    {
        $businessHour = BusinessHourModel::firstWhere('uuid', $uuid->getValue());
        if ($businessHour == null) {
            throw new NotFoundException();
        }

        return $businessHour;
    }

    public function findBySchedule(
        HospitalId $hospitalId,
        DayOfWeek $dayOfWeek,
        TimePeriod $timePeriod
    ): ?BusinessHourModel {
        return BusinessHourModel::where('hospital_id', $hospitalId->getValue())
            ->where('day_of_week', $dayOfWeek)
            ->firstWhere('time_period', $timePeriod);
    }

    public function getListByHospitalId(HospitalId $hospitalId): Collection
    {
        return BusinessHourModel::where('hospital_id', $hospitalId->getValue())
            ->orderBy('day_of_week')
            ->orderBy('time_period')
            ->get();
    }

    public function create(BusinessHour $businessHourEntity): bool
    {
        $model = $this->businessHourFactory->entityToModel($businessHourEntity);
        return $model->save();
    }

    public function update(BusinessHour $businessHourEntity): bool
    {
        $model = BusinessHourModel::findOrFail($businessHourEntity->getId()->getValue());

        $model->start_time = $businessHourEntity->getStartTime()->getValue();
        $model->end_time   = $businessHourEntity->getEndTime()->getValue();

        return $model->update();
    }

    public function delete(DeletableBusinessHourId $id): bool
    {
        $model = BusinessHourModel::findOrFail($id->getValue());

        return $model->delete();
    }
}
