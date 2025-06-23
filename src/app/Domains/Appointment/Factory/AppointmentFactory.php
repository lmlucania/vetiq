<?php

declare(strict_types=1);

namespace App\Domains\Appointment\Factory;

use App\Domains\Appointment\Entity\Appointment as AppointmentEntity;
use App\Domains\Appointment\ValueObjects\AppointmentAt;
use App\Domains\Appointment\ValueObjects\AppointmentId;
use App\Domains\Hospital\Repositories\ValueObject\HospitalId;
use App\Domains\Menu\ValueObjects\MenuId;
use App\Domains\Pet\ValueObjects\PetId;
use App\Domains\Vet\ValueObjects\VetId;
use App\Exceptions\DomainException;
use App\Models\Appointment as AppointmentModel;
use App\Models\AppointmentStatusHistory;

class AppointmentFactory
{
    public function modelToEntity(
        AppointmentModel $appointment,
        AppointmentStatusHistory $statusHistory
    ): AppointmentEntity {
        if ($appointment->id != $statusHistory->appointment_id) {
            throw new DomainException('Appointment IDとStatusHistoryのappointment_idが一致しません。データ不整合の可能性があります。');
        }

        return new AppointmentEntity(
            id: new AppointmentId($appointment->id),
            petId: new PetId($appointment->pet_id),
            hospitalId: new HospitalId($appointment->hospital_id),
            menuId: new MenuId($appointment->menu_id),
            vetId: $appointment->vet_id ? new VetId($appointment->vet_id) : null,
            appointmentAt: new AppointmentAt($appointment->appointment_at->format('Y-m-d H:i')),
            status: $statusHistory->status,
            hospitalMemo: null,
        );
    }
}
