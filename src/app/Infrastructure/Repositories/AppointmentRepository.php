<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function create(
        int $petId,
        int $hospitalId,
        int $menuId,
        ?int $vetId,
        Carbon $appointmentAt
    ): Appointment {
        return Appointment::create([
            'pet_id'         => $petId,
            'hospital_id'    => $hospitalId,
            'menu_id'        => $menuId,
            'vet_id'         => $vetId,
            'appointment_at' => $appointmentAt,
        ]);
    }
}
