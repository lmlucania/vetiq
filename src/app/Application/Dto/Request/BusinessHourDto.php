<?php

declare(strict_types=1);

namespace App\Application\Dto\Request;

use App\Domains\Schedule\Enum\DayOfWeek;

class BusinessHourDto
{
    private function __construct(
        private int $dayOfWeek,
        private array $periods
    ) {
    }

    public function getDayOfWeek(): int
    {
        return $this->dayOfWeek;
    }

    /**
     * @return BusinessHourPeriodDto[]
     */
    public function getPeriods(): array
    {
        return $this->periods;
    }

    public static function fromPrimitive(int $dayOfWeek, array $periods): self
    {
        return new self(
            dayOfWeek: $dayOfWeek,
            periods: array_map(fn ($period) => BusinessHourPeriodDto::fromPrimitive(
                timePeriod: $period['time_period'],
                startTime: $period['start_time'],
                endTime: $period['end_time'],
            ), $periods),
        );
    }
}
