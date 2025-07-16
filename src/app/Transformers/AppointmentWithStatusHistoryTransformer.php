<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Appointment;
use League\Fractal\TransformerAbstract;

class AppointmentWithStatusHistoryTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['pet', 'menu', 'hospital', 'vet'];

    public function transform(Appointment $appointment)
    {
        return [
            'id'               => $appointment->id,
            'appointment_at'   => $appointment->appointment_at->format('Y-m-d H:i'),
            'created_at'       => $appointment->created_at,
            'updated_at'       => $appointment->updated_at,
            'status_histories' => fractal($appointment->statusHistories, new AppointmentStatusHistoryTransformer()),
        ];
    }

    public function includePet(Appointment $appointment)
    {
        return $this->item($appointment->pet, new PetTransformer());
    }

    public function includeMenu(Appointment $appointment)
    {
        return $this->item($appointment->menu, new MenuTransformer());
    }

    public function includeHospital(Appointment $appointment)
    {
        return $this->item($appointment->hospital, new HospitalTransformer());
    }

    public function includeVet(Appointment $appointment)
    {
        return $appointment->vet !== null ? $this->item($appointment->vet, new VetTransformer()) : null;
    }
}
