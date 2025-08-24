<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital\BusinessHour;

use App\Application\Dto\Request\BusinessHourDto;
use App\Domains\Schedule\Enum\DayOfWeek;
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
            'day_of_week'          => ['required', 'integer', new Enum(DayOfWeek::class)],
            'periods'              => ['required', 'array', 'max:5'],
            'periods.*.start_time' => ['required', 'date_format:H:i'],
            'periods.*.end_time'   => ['required', 'date_format:H:i', 'after:periods.*.start_time'],
        ];
    }

    private function getDayOfWeek(): int
    {
        return $this->validated('day_of_week');
    }

    private function getPeriods(): array
    {
        return $this->validated('periods');
    }

    public function getDto(): BusinessHourDto
    {
        return BusinessHourDto::fromPrimitive(
            dayOfWeek: $this->getDayOfWeek(),
            periods: $this->getPeriods(),
        );
    }
}
