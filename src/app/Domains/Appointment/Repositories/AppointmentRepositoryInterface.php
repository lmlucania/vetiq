<?php

declare(strict_types=1);

namespace App\Domains\Appointment\Repositories;

use App\Domains\Appointment\Entity\Appointment as AppointmentEntity;
use App\Models\Appointment;

interface AppointmentRepositoryInterface
{
    public function getByUserIdAndId(int $userId, int $id): Appointment;

    public function getWithStatusHistoriesByUserIdAndId(int $userId, int $id): Appointment;

    public function create(AppointmentEntity $entity): AppointmentEntity;
}
