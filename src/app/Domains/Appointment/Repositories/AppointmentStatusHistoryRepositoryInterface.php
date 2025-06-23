<?php

declare(strict_types=1);

namespace App\Domains\Appointment\Repositories;

use App\Domains\Appointment\Entity\Appointment;
use App\Models\AppointmentStatusHistory;
use Illuminate\Foundation\Auth\User;

interface AppointmentStatusHistoryRepositoryInterface
{
    public function getLatestByAppointmentId(int $appointmentId): AppointmentStatusHistory;

    public function create(
        Appointment $appointmentEntity,
        User $modifier,
    ): AppointmentStatusHistory;
}
