<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\ValueObjects;

use App\Exceptions\InvalidArgumentException;
use Carbon\CarbonImmutable;

class StartTime
{
    private CarbonImmutable $startTime;

    public function __construct(string $time)
    {
        $parsed = CarbonImmutable::createFromFormat('H:i', $time);

        if (! $parsed || $parsed->format('H:i') !== $time) {
            throw new InvalidArgumentException("開始時刻の形式が不正です: {$time}");
        }

        $this->startTime = $parsed;
    }

    public function getValue(): CarbonImmutable
    {
        return $this->startTime;
    }
}
