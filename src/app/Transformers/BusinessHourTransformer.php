<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Application\Dto\Response\BusinessHourDto;
use League\Fractal\TransformerAbstract;

class BusinessHourTransformer extends TransformerAbstract
{
    public function transform(BusinessHourDto $businessHourDto)
    {
        return [
            'uuid'        => $businessHourDto->getUuid()->getValue(),
            'day_of_week' => $businessHourDto->getDayOfWeek(),
            'time_period' => $businessHourDto->getTimePeriod(),
            'start_time'  => $businessHourDto->getStartTime()->getValue()->format('H:i'),
            'end_time'    => $businessHourDto->getEndTime()->getValue()->format('H:i'),
        ];
    }
}
