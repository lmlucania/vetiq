<?php

declare(strict_types=1);

namespace App\Domains\Appointment\Repositories;

use App\Models\Appointment;
use Carbon\Carbon;

interface AppointmentRepositoryInterface
{
    public function create(
        int $petId,
        int $hospitalId,
        int $menuId,
        ?int $vetId,
        Carbon $appointmentAt
    ): Appointment;
}
