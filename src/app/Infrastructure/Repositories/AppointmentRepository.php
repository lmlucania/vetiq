<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Appointment\Entity\Appointment as AppointmentEntity;
use App\Domains\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domains\Appointment\ValueObjects\AppointmentId;
use App\Models\Appointment;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function getByUserIdAndId(int $userId, int $id): Appointment
    {
        return Appointment::query()
            ->join('pets', 'appointments.pet_id', '=', 'pets.id')
            ->where('appointments.id', $id)
            ->where('pets.user_id', $userId)
            ->firstOrFail();
    }

    public function create(
        AppointmentEntity $entity
    ): AppointmentEntity {
        $model = Appointment::create([
            'pet_id'         => $entity->getPetId()->getValue(),
            'hospital_id'    => $entity->getHospitalId()->getValue(),
            'menu_id'        => $entity->getMenuId()->getValue(),
            'vet_id'         => $entity->getVetId()?->getValue(),
            'appointment_at' => $entity->getAppointmentAt()->getValue(),
        ]);

        return $entity->withId(new AppointmentId($model->id));
    }
}
