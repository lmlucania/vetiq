<?php

declare(strict_types=1);

namespace App\Application\Dto\Request;

class TimeRangeDto
{
    public function __construct(
        private string $startTime,
        private string $endTime,
    ) {
    }

    public static function fromPrimitive(string $startTime, string $endTime)
    {
        return new self(
            startTime: $startTime,
            endTime: $endTime,
        );
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function getEndTime(): ?string
    {
        return $this->endTime;
    }

    public function empty(): bool
    {
        return empty($this->startTime) || empty($this->endTime);
    }
}
