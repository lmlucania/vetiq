<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\ExceptionHour;
use League\Fractal\TransformerAbstract;

class ExceptionHourTransformer extends TransformerAbstract
{
    public function transform(ExceptionHour $exceptionHour)
    {
        return [
            'id'          => $exceptionHour->id,
            'date'        => $exceptionHour->date,
            'time_period' => $exceptionHour->time_period->value,
            'start_time'  => $exceptionHour->start_time?->format('H:i'),
            'end_time'    => $exceptionHour->end_time?->format('H:i'),
            'is_closed'   => $exceptionHour->is_closed,
            'reason'      => $exceptionHour->reason,
        ];
    }
}
