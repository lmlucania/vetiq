<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital;

use App\Domains\BusinessHour\Enum\DayOfWeek;
use App\Domains\BusinessHour\Enum\TimePeriod;
use App\Http\Requests\Base\ApiRequest;
use Illuminate\Validation\Rules\Enum;

class StoreBusinessHourRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @OA\Schema(
     *     schema="Requests/Hospital/StoreBusinessHourRequest",
     *     type="object",
     *     required={"day_of_week", "periods", "periods.*.period_type", "periods.*.start_time", "periods.*.end_time"},
     *     description="指定した曜日の予約受付時間の作成/更新",
     *     @OA\Property(
     *          property="day_of_week",
     *          ref="#/components/schemas/Enum~1DayOfWeek",
     *      ),
     *      @OA\Property(
     *          property="periods",
     *          type="array",
     *          maxItems=2,
     *          description="予約受付時間のリスト（最大2件）",
     *          @OA\Items(type="object",
     *              @OA\Property(
     *                  property="time_period",
     *                  ref="#/components/schemas/Enum~1TimePeriod",
     *              ),
     *              @OA\Property(
     *                  property="start_time",
     *                  type="string",
     *                  format="time",
     *                  example="09:00",
     *                  description="開始時間（HH:MM形式）"
     *              ),
     *              @OA\Property(
     *                  property="end_time",
     *                  type="string",
     *                  format="time",
     *                  example="12:00",
     *                  description="終了時間（HH:MM形式）"
     *              )
     *          )
     *      )
     * )
     */
    public function rules(): array
    {
        return [
            'day_of_week'           => ['required', 'integer', new Enum(DayOfWeek::class)],
            'periods'               => ['required', 'array', 'max:2'],
            'periods.*.time_period' => ['required', 'integer', new Enum(TimePeriod::class)],
            'periods.*.start_time'  => ['required', 'date_format:H:i'],
            'periods.*.end_time'    => ['required', 'date_format:H:i', 'after:periods.*.start_time'],
        ];
    }
}
