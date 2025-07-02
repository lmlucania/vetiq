<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\AppointmentStatusHistory;
use League\Fractal\TransformerAbstract;

class AppointmentStatusHistoryTransformer extends TransformerAbstract
{
    public function transform(AppointmentStatusHistory $statusHistory)
    {
        return [
            'id'         => $statusHistory->id,
            'status'     => $statusHistory->status,
            'created_at' => $statusHistory->created_at,
            'updated_at' => $statusHistory->updated_at,
        ];
    }
}
