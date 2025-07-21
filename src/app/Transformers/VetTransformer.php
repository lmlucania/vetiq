<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Vet;
use League\Fractal\TransformerAbstract;

class VetTransformer extends TransformerAbstract
{
    public function transform(Vet $vet)
    {
        return [
            'id'                 => $vet->id,
            'last_name'          => $vet->last_name,
            'first_name'         => $vet->first_name,
            'accept_appointment' => $vet->accept_appointment,
            'remark'             => $vet->remark,
        ];
    }

    public static function fromJoin(
        int $id,
        string $lastName,
        string $firstName,
        int $acceptAppointment,
    ): array {
        return [
            'id'                 => $id,
            'last_name'          => $lastName,
            'first_name'         => $firstName,
            'accept_appointment' => (bool)$acceptAppointment,
        ];
    }
}
