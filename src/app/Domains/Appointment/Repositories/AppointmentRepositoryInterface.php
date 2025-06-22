<?php

declare(strict_types=1);

namespace App\Domains\Appointment\Repositories;

use App\Domains\Appointment\Enum\AppointmentStatus;
use App\Models\Appointment;
use App\Models\AppointmentStatusHistory;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;

interface AppointmentRepositoryInterface
{
    public function getByUserIdAndId(int $userId, int $id): Appointment;

    public function create(
        int $petId,
        int $hospitalId,
        int $menuId,
        ?int $vetId,
        Carbon $appointmentAt,
        AppointmentStatus $status,
        User $modifier,
        ?string $hospitalMemo
    ): bool;

    public function createStatusHistory(
        int $appointmentId,
        AppointmentStatus $status,
        User $modifier,
        ?string $hospitalMemo
    ): AppointmentStatusHistory;
}
