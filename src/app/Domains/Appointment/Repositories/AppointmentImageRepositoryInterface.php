<?php

declare(strict_types=1);

namespace App\Domains\Appointment\Repositories;

interface AppointmentImageRepositoryInterface
{
    public function saveMany(int $appointmentId, array $images): array;

    public function deleteMany(array $paths): bool;
}
