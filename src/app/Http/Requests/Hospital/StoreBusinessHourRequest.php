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
