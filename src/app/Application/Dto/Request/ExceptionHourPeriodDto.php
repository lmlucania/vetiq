<?php

declare(strict_types=1);

namespace App\Application\Dto\Request;

class ExceptionHourPeriodDto
{
    public function __construct(
        private int $timePeriod,
        private ?string $startTime,
        private ?string $endTime,
        private bool $isClosed,
        private ?string $reason,
    ) {
    }

    public static function fromPrimitive(int $timePeriod, ?string $startTime, ?string $endTime, bool $isClosed, ?string $reason)
    {
        return new self(
            timePeriod: $timePeriod,
            startTime: $startTime,
            endTime: $endTime,
            isClosed: $isClosed,
            reason: $reason,
        );
    }

    public function getTimePeriod(): int
    {
        return $this->timePeriod;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function getEndTime(): ?string
    {
        return $this->endTime;
    }

    public function isClosed(): bool
    {
        return $this->isClosed;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }
}
