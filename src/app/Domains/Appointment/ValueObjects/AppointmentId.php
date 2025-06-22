<?php

namespace App\Domains\Appointment\ValueObjects;

class AppointmentId
{
    public function __construct(
        private int $id,
    ) {
    }

    public function getValue(): int
    {
        return $this->id;
    }
}
