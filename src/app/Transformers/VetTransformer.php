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
            'uuid'               => $vet->uuid,
            'last_name'          => $vet->last_name,
            'first_name'         => $vet->first_name,
            'accept_appointment' => $vet->accept_appointment,
            'remark'             => $vet->remark,
        ];
    }
}
