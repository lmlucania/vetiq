<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Appointment\Enum\AppointmentStatus;
use App\Domains\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Models\Appointment;
use App\Models\AppointmentStatusHistory;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function getByUserIdAndId(int $userId, int $id): Appointment
    {
        return Appointment::query()
            ->join('pets', 'appointments.pet_id', '=', 'pets.id')
            ->where('appointments.id', $id)
            ->where('pets.user_id', $userId)
            ->select('appointments.*') // 必須：Appointment モデルにマッピングさせるため
            ->firstOrFail();
    }

    public function create(
        int $petId,
        int $hospitalId,
        int $menuId,
        ?int $vetId,
        Carbon $appointmentAt,
        AppointmentStatus $status,
        User $modifier,
        ?string $hospitalMemo
    ): bool {
        try {
            DB::transaction(function () use (
                $petId,
                $hospitalId,
                $menuId,
                $vetId,
                $appointmentAt,
                $status,
                $modifier,
                $hospitalMemo
            ) {
                $appointment = Appointment::create([
                    'pet_id'         => $petId,
                    'hospital_id'    => $hospitalId,
                    'menu_id'        => $menuId,
                    'vet_id'         => $vetId,
                    'appointment_at' => $appointmentAt,
                ]);

                $this->createStatusHistory($appointment->id, $status, $modifier, $hospitalMemo);
            });

            return true;
        } catch (Throwable $e) {
            Log::error('Appointment create failed', ['error' => $e]);
            return false;
        }
    }

    public function createStatusHistory(
        int $appointmentId,
        AppointmentStatus $status,
        User $modifier,
        ?string $hospitalMemo
    ): AppointmentStatusHistory {
        return AppointmentStatusHistory::create([
            'appointment_id' => Appointment::findOrFail($appointmentId)->id,
            'status'         => $status,
            'modifier_type'  => get_class($modifier),
            'modifier_id'    => $modifier->id,
            'hospital_memo'  => $hospitalMemo,
        ]);
    }
}
