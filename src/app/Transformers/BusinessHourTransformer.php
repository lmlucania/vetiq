<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\BusinessHour;
use League\Fractal\TransformerAbstract;

class BusinessHourTransformer extends TransformerAbstract
{
    public function transform(BusinessHour $businessHour)
    {
        return [
            'id'          => $businessHour->id,
            'day_of_week' => $businessHour->day_of_week->value,
            'start_time'  => $businessHour->start_time->format('H:i'),
            'end_time'    => $businessHour->end_time->format('H:i'),
        ];
    }
}
