<?php

declare(strict_types=1);

namespace App\Application\Dto\Request;

use App\Domains\Schedule\Enum\TimePeriod;

class BusinessHourPeriodDto
{
    public function __construct(
        private TimePeriod $timePeriod,
        private string $startTime,
        private string $endTime,
    ) {
    }

    public static function fromPrimitive(int $timePeriod, string $startTime, string $endTime)
    {
        return new self(
            timePeriod: TimePeriod::from($timePeriod),
            startTime: $startTime,
            endTime: $endTime,
        );
    }

    public function getTimePeriod(): TimePeriod
    {
        return $this->timePeriod;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function getEndTime(): string
    {
        return $this->endTime;
    }
}
