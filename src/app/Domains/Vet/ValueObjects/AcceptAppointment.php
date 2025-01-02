<?php

declare(strict_types=1);

namespace App\Domains\Vet\ValueObjects;

final class AcceptAppointment
{
    public function __construct(
        private bool $acceptAppointment
    ) {
    }

    public function getValue(): bool
    {
        return $this->acceptAppointment;
    }
}
