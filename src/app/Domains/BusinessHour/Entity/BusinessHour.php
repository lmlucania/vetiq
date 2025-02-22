<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\Entity;

use App\Domains\BusinessHour\Enum\DayOfWeek;
use App\Domains\BusinessHour\ValueObjects\BusinessHourId;
use App\Domains\BusinessHour\ValueObjects\BusinessHourUuid;
use App\Domains\BusinessHour\ValueObjects\EndTime;
use App\Domains\BusinessHour\ValueObjects\StartTime;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Exceptions\DomainException;

final class BusinessHour
{
    public function __construct(
        private BusinessHourId $id,
        private BusinessHourUuid $uuid,
        private HospitalId $hospitalId,
        private DayOfWeek $dayOfWeek,
        private StartTime $startTime,
        private EndTime $endTime,
    )
    {
        if ($this->startTime->getValue() < $this->endTime->getValue()) {
            throw new DomainException('開始時刻＜終了時刻で指定してください。');
        }
    }

    public function getId(): BusinessHourId
    {
        return $this->id;
    }

    public function getUuid(): BusinessHourUuid
    {
        return $this->uuid;
    }

    public function getHospitalId(): HospitalId
    {
        return $this->hospitalId;
    }

    public function getDayOfWeek(): DayOfWeek
    {
        return $this->dayOfWeek;
    }

    public function getStartTime(): StartTime
    {
        return $this->startTime;
    }

    public function getEndTime(): EndTime
    {
        return $this->endTime;
    }
}
