<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Appointment\Enum\AppointmentStatus;
use App\Domains\Appointment\Repositories\AppointmentStatusHistoryRepositoryInterface;
use App\Models\AppointmentStatusHistory;
use Illuminate\Foundation\Auth\User;

class AppointmentStatusHistoryRepository implements AppointmentStatusHistoryRepositoryInterface
{
    public function create(
        int $appointmentId,
        AppointmentStatus $status,
        User $modifier,
        string $hospitalMemo
    ): AppointmentStatusHistory {
        return AppointmentStatusHistory::create([
            'appointment_id' => $appointmentId,
            'status'         => $status,
            'modifier_type'  => get_class($modifier),
            'modifier_id'    => $modifier->id,
            'hospital_memo'  => $hospitalMemo,
        ]);
    }
}
