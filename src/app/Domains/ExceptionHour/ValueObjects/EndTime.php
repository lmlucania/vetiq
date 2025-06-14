<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\ValueObjects;

use App\Exceptions\InvalidArgumentException;
use Carbon\CarbonImmutable;

class EndTime
{
    private CarbonImmutable $endTime;

    public function __construct(string $time)
    {
        $parsed = CarbonImmutable::createFromFormat('H:i', $time);

        if (! $parsed || $parsed->format('H:i') !== $time) {
            throw new InvalidArgumentException("終了時刻の形式が不正です: {$time}");
        }

        $this->endTime = $parsed;
    }

    public function getValue(): CarbonImmutable
    {
        return $this->endTime;
    }
}
