<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Appointment;
use League\Fractal\TransformerAbstract;

class AppointmentTransformer extends TransformerAbstract
{
    public function transform(Appointment $appointment)
    {
        return [
            'id'             => $appointment->appt,
            'appointment_at' => $appointment->appointment_at->format('Y-m-d H:i'),
        ];
    }
}
