<?php

declare(strict_types=1);

namespace App\Domains\Appointment\Repositories;

use App\Domains\Appointment\Enum\AppointmentStatus;
use App\Models\AppointmentStatusHistory;
use Illuminate\Foundation\Auth\User;

interface AppointmentStatusHistoryRepositoryInterface
{
    public function create(
        int $appointmentId,
        AppointmentStatus $status,
        User $modifier,
        string $hospitalMemo
    ): AppointmentStatusHistory;
}
