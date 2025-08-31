<?php

declare(strict_types=1);

namespace App\Domains\Appointment\Repositories;

interface AppointmentImageAttachRepositoryInterface
{
    public function attach(int $appointmentId, array $paths): bool;
}
