<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital\ExceptionHour;

use App\Application\Dto\Request\ExceptionHourDto;
use App\Domains\Schedule\Enum\TimePeriod;
use App\Http\Requests\Base\ApiRequest;
use Illuminate\Validation\Rules\Enum;

class StoreExceptionHourRequest extends ApiRequest
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
            'date'                  => ['required', 'date_format:Y-m-d'],
            'periods'               => ['required', 'array', 'max:2'],
            'periods.*.start_time'  => ['nullable', 'date_format:H:i'],
            'periods.*.end_time'    => ['nullable', 'date_format:H:i', 'after:periods.*.start_time'],
            'periods.*.is_closed'   => ['required', 'bool'],
            'periods.*.reason'      => ['nullable', 'string'],
        ];
    }

    private function getDate(): string
    {
        return $this->validated('date');
    }

    private function getPeriods(): array
    {
        return $this->validated('periods');
    }

    public function getDto(): ExceptionHourDto
    {
        return ExceptionHourDto::fromPrimitive(
            date: $this->getDate(),
            periods: $this->getPeriods(),
        );
    }
}
