<?php

namespace App\Domains\Appointment\ValueObjects;

use App\Exceptions\InvalidArgumentException;
use Carbon\CarbonImmutable;

class AppointmentAt
{
    private CarbonImmutable $appointmentAt;

    public function __construct(string $time)
    {
        $parsed = CarbonImmutable::createFromFormat('H:i', $time);

        if (! $parsed || $parsed->format('Y-m-d H:i') !== $time) {
            throw new InvalidArgumentException("予約時刻の形式が不正です: {$time}");
        }

        $this->appointmentAt = $parsed;
    }

    public function getValue(): CarbonImmutable
    {
        return $this->appointmentAt;
    }

    public function isPast(): bool
    {
        return $this->appointmentAt->lt(now());
    }

}
