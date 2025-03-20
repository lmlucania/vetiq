<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Application\Dto\Response\BusinessHourDto;
use League\Fractal\TransformerAbstract;

class BusinessHourTransformer extends TransformerAbstract
{
    /**
     * @OA\Schema(
     *     schema="Response/BusinessHour",
     *     description="受付時間",
     *     type="object",
     *     @OA\Property(
     *         property="uuid",
     *         type="string",
     *         description="受付時間ID",
     *         example="1667cff9-71e5-4719-953c-e074507d2d3d",
     *     ),
     *     @OA\Property(
     *         property="day_of_week",
     *         type="integer",
     *         description="曜日",
     *         example=0,
     *     ),
     *     @OA\Property(
     *         property="time_period",
     *         type="integer",
     *         description="午前午後",
     *         example=0,
     *     ),
     *     @OA\Property(
     *         property="start_time",
     *         type="string",
     *         format="time",
     *         description="受付開始時間",
     *         example="9:00",
     *     ),
     *     @OA\Property(
     *         property="end_time",
     *         type="string",
     *         format="time",
     *         description="受付終了時間",
     *         example="12:00",
     *     ),
     * )
     */
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
