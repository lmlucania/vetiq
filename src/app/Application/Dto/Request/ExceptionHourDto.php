<?php

declare(strict_types=1);

namespace App\Application\Dto\Request;

class ExceptionHourDto
{
    private function __construct(
        private string $date,
        private array $periods
    ) {
    }

    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return ExceptionHourPeriodDto[]
     */
    public function getPeriods(): array
    {
        return $this->periods;
    }

    public static function fromPrimitive(string $date, array $periods): self
    {
        return new self(
            date: $date,
            periods: array_map(fn ($period) => ExceptionHourPeriodDto::fromPrimitive(
                startTime: $period['start_time'],
                endTime: $period['end_time'],
                isClosed: $period['is_closed'],
                reason: $period['reason'],
            ), $periods),
        );
    }
}
