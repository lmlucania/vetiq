<?php

declare(strict_types=1);

namespace App\Application\Dto\Response;

use App\Domains\BusinessHour\Enum\DayOfWeek;
use App\Domains\BusinessHour\Enum\TimePeriod;
use App\Domains\BusinessHour\ValueObjects\BusinessHourUuid;
use App\Domains\BusinessHour\ValueObjects\EndTime;
use App\Domains\BusinessHour\ValueObjects\StartTime;

class BusinessHourDto
{
    public function __construct(
        private BusinessHourUuid $uuid,
        private DayOfWeek $dayOfWeek,
        private TimePeriod $timePeriod,
        private StartTime $startTime,
        private EndTime $endTime,
    ) {
    }

    public function getEndTime(): EndTime
    {
        return $this->endTime;
    }

    public function getStartTime(): StartTime
    {
        return $this->startTime;
    }

    public function getDayOfWeek(): DayOfWeek
    {
        return $this->dayOfWeek;
    }

    public function getTimePeriod(): TimePeriod
    {
        return $this->timePeriod;
    }

    public function getUuid(): BusinessHourUuid
    {
        return $this->uuid;
    }
}
