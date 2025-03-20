<?php

declare(strict_types=1);

namespace App\Application\Dto\Request;

use App\Domains\BusinessHour\Enum\TimePeriod;
use App\Domains\BusinessHour\ValueObjects\EndTime;
use App\Domains\BusinessHour\ValueObjects\StartTime;

class BusinessHourPeriodDto
{
    public function __construct(
        private TimePeriod $timePeriod,
        private StartTime $startTime,
        private EndTime $endTime,
    ) {
    }

    public static function fromPrimitive(int $timePeriod, string $startTime, string $endTime)
    {
        return new self(
            timePeriod: TimePeriod::fromInt($timePeriod),
            startTime: StartTime::fromString($startTime),
            endTime: EndTime::fromString($endTime),
        );
    }

    public function getTimePeriod(): TimePeriod
    {
        return $this->timePeriod;
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
