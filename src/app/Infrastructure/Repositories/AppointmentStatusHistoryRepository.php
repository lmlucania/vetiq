<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Appointment\Entity\Appointment;
use App\Domains\Appointment\Repositories\AppointmentStatusHistoryRepositoryInterface;
use App\Models\AppointmentStatusHistory;
use Illuminate\Foundation\Auth\User;

class AppointmentStatusHistoryRepository implements AppointmentStatusHistoryRepositoryInterface
{
    public function getLatestByAppointmentId(int $appointmentId): AppointmentStatusHistory
    {
        return AppointmentStatusHistory::query()
            ->where('appointment_id', $appointmentId)
            ->orderByDesc('created_at')
            ->firstOrFail();
    }

    public function create(
        Appointment $appointmentEntity,
        User $modifier,
    ): AppointmentStatusHistory {
        return AppointmentStatusHistory::create([
            'appointment_id' => $appointmentEntity->getAppointmentId()->getValue(),
            'status'         => $appointmentEntity->getStatus()->value,
            'modifier_type'  => get_class($modifier),
            'modifier_id'    => $modifier->id,
            'hospital_memo'  => $appointmentEntity->getHospitalMemo()?->getValue(),
        ]);
    }
}
