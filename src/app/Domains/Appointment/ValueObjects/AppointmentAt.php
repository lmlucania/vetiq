<?php

declare(strict_types=1);

namespace App\Domains\Appointment\ValueObjects;

use App\Exceptions\InvalidArgumentException;
use Carbon\CarbonImmutable;

class AppointmentAt
{
    private CarbonImmutable $appointmentAt;

    public function __construct(string $date)
    {
        $parsed = CarbonImmutable::createFromFormat('Y-m-d H:i', $date);

        if (! $parsed || $parsed->format('Y-m-d H:i') !== $date) {
            throw new InvalidArgumentException("予約時刻の形式が不正です: {$date}");
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
