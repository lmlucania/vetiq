<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\Factory;

use App\Application\Dto\Response\ExceptionHourDto;
use App\Domains\ExceptionHour\Entity\ExceptionHour;
use App\Domains\ExceptionHour\ValueObjects\Date;
use App\Domains\ExceptionHour\ValueObjects\EndTime;
use App\Domains\ExceptionHour\ValueObjects\ExceptionHourId;
use App\Domains\ExceptionHour\ValueObjects\ExceptionHourUuid;
use App\Domains\ExceptionHour\ValueObjects\IsClosed;
use App\Domains\ExceptionHour\ValueObjects\Reason;
use App\Domains\ExceptionHour\ValueObjects\StartTime;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Models\ExceptionHourModel;

class ExceptionHourFactory
{
    public function modelToEntity(ExceptionHourModel $model): ExceptionHour
    {
        return new ExceptionHour(
            new ExceptionHourId($model->id),
            new ExceptionHourUuid($model->uuid),
            new HospitalId($model->hospital_id),
            new Date($model->date),
            $model->time_period,
            is_null($model->start_time) ? null : StartTime::fromCarbon($model->start_time),
            is_null($model->end_time) ? null : EndTime::fromCarbon($model->end_time),
            new IsClosed($model->is_closed),
            is_null($model->reason) ? null : new Reason($model->reason),
        );
    }

    public function entityToModel(ExceptionHour $entity): ExceptionHourModel
    {
        $model = new ExceptionHourModel();

        $model->id          = $entity->getExceptionHourId()->getValue();
        $model->uuid        = $entity->getExceptionHourUuid()->getValue();
        $model->hospital_id = $entity->getHospitalId()->getValue();
        $model->date        = $entity->getDate()->getValue();
        $model->day_of_week = $entity->getTimePeriod();
        $model->start_time  = $entity->getStartTime()?->getValue();
        $model->end_time    = $entity->getEndTime()?->getValue();
        $model->is_closed   = $entity->getIsClosed()->getValue();
        $model->reason      = $entity->getReason()?->getValue();

        return $model;
    }

    public function entityToDto(ExceptionHour $entity): ExceptionHourDto
    {
        return new ExceptionHourDto(
            $entity->getExceptionHourUuid(),
            $entity->getDate(),
            $entity->getTimePeriod(),
            $entity->getStartTime(),
            $entity->getEndTime(),
            $entity->getIsClosed(),
            $entity->getReason(),
        );
    }
}
