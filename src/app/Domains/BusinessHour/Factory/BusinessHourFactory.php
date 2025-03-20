<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\Factory;

use App\Application\Dto\Response\BusinessHourDto;
use App\Domains\BusinessHour\Entity\BusinessHour;
use App\Domains\BusinessHour\Enum\DayOfWeek;
use App\Domains\BusinessHour\Enum\TimePeriod;
use App\Domains\BusinessHour\ValueObjects\BusinessHourId;
use App\Domains\BusinessHour\ValueObjects\BusinessHourUuid;
use App\Domains\BusinessHour\ValueObjects\EndTime;
use App\Domains\BusinessHour\ValueObjects\StartTime;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Models\BusinessHourModel;
use Carbon\Carbon;

class BusinessHourFactory
{
    public function modelToEntity(BusinessHourModel $model): BusinessHour
    {
        return new BusinessHour(
            new BusinessHourId($model->id),
            new BusinessHourUuid($model->uuid),
            new HospitalId($model->hospital_id),
            $model->day_of_week,
            $model->time_period,
            StartTime::fromCarbon($model->start_time),
            EndTime::fromCarbon($model->end_time),
        );
    }

    public function entityToModel(BusinessHour $entity): BusinessHourModel
    {
        $model = new BusinessHourModel();

        $model->id          = $entity->getId()->getValue();
        $model->uuid        = $entity->getUuid()->getValue();
        $model->hospital_id = $entity->getHospitalId()->getValue();
        $model->day_of_week = $entity->getDayOfWeek();
        $model->time_period = $entity->getTimePeriod();
        $model->start_time  = $entity->getStartTime()->getValue();
        $model->end_time    = $entity->getEndTime()->getValue();

        return $model;
    }

    public function entityToDto(BusinessHour $entity): BusinessHourDto
    {
        return new BusinessHourDto(
            $entity->getUuid(),
            $entity->getDayOfWeek(),
            $entity->getTimePeriod(),
            $entity->getStartTime(),
            $entity->getEndTime(),
        );
    }

    public function createEntityFromPrimitive(
        int $id,
        string $uuid,
        int $hospitalId,
        int $dayOfWeek,
        int $timePeriod,
        Carbon $startTime,
        Carbon $endTime,
    ): BusinessHour {
        return new BusinessHour(
            new BusinessHourId($id),
            new BusinessHourUuid($uuid),
            new HospitalId($hospitalId),
            DayOfWeek::fromInt($dayOfWeek),
            TimePeriod::fromInt($timePeriod),
            StartTime::fromCarbon($startTime),
            EndTime::fromCarbon($endTime),
        );
    }
}
