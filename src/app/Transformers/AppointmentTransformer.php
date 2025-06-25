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
            'appointment_at' => $appointment->appt_appointment_at->format('Y-m-d H:i'),
            'status'         => $appointment->status,
            'created_at'     => $appointment->appt_created_at,
            'updated_at'     => $appointment->status_created_at,
            'pet'            => PetTransformer::fromJoin(
                id: $appointment->pet_id,
                name: $appointment->pet_name,
                gender: $appointment->pet_gender,
                birthday: $appointment->pet_birthday,
                startedCareAt: $appointment->pet_started_care_at,
                remark: $appointment->pet_remark,
            ),
            'hospital' => HospitalTransformer::fromJoin(
                hospitalId: $appointment->hospital_id,
                name: $appointment->hospital_name,
                phone: $appointment->hospital_phone,
                postCode: $appointment->hospital_post_code,
                prefecture: $appointment->hospital_prefecture,
                address1: $appointment->hospital_address1,
                address2: $appointment->hospital_address2,
            ),
            'menu' => MenuTransformer::fromJoin(
                menuId: $appointment->menu_id,
                name: $appointment->menu_name,
                detail: $appointment->menu_detail,
                requiredTime: $appointment->menu_required_time,
                isPublished: $appointment->menu_is_published,
            ),
            'vet' => $appointment->vet_id !== null ? VetTransformer::fromJoin(
                id: $appointment->vet_id,
                lastName: $appointment->vet_last_name,
                firstName: $appointment->vet_first_name,
                acceptAppointment: $appointment->vet_accept_appointment,
            ) : null,
        ];
    }
}
