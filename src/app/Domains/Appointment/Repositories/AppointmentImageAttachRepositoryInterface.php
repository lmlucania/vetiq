<?php

declare(strict_types=1);

namespace App\Domains\Appointment\Repositories;

interface AppointmentImageAttachRepositoryInterface
{
    public function attachMany(int $appointmentId, array $insertRows): bool;
}
