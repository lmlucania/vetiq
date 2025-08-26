<?php

declare(strict_types=1);

namespace App\Http\Requests\User\HospitalInfo;

use App\Application\Dto\Request\TimeRangeDto;
use App\Domains\Schedule\Enum\DayOfWeek;
use App\Http\Requests\Base\ApiRequest;
use Illuminate\Validation\Rules\Enum;

class IndexHospitalRequest extends ApiRequest
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
            'page'          => ['nullable', 'integer', 'min:1'],
            'per_page'      => ['nullable', 'integer', 'min:1', 'max:500'],
            'sort'          => ['nullable', 'array'],
            'sort.*'        => ['string'],
            'tags'          => ['nullable', 'array'],
            'tags.*'        => ['integer'],
            'prefectures'   => ['nullable', 'array'],
            'prefectures.*' => ['integer'],
            'keyword'       => ['nullable', 'string'],
            'addresses'     => ['nullable', 'array'],
            'addresses.*'   => ['string'],
            'date'          => ['nullable', 'date_format:Y-m-d'],
            'start_time'    => ['nullable', 'date_format:H:i', 'required_with:end_time'],
            'end_time'      => ['nullable', 'date_format:H:i', 'required_with:start_time', 'after_or_equal:start_time'],
            'day_of_week'     => ['nullable', 'array'],
            'day_of_week.*'          => ['integer', new Enum(DayOfWeek::class)],
        ];
    }

    public function getPage():int
    {
        return (int)$this->query('page') ?: 1;
    }

    public function getPerPage():int
    {
        return (int)$this->query('per_page') ?: 50;
    }

    public function getSort():array
    {
        return $this->query('sort', []);
    }

    public function getTags():array
    {
        return $this->query('tags', []);
    }

    public function getAddresses():array
    {
        return $this->query('addresses', []);
    }

    public function getPrefectures():array
    {
        return $this->query('prefectures', []);
    }

    public function getKeyword(): string
    {
        return $this->query('keyword', '');
    }

    public function getDate(): string
    {
        return $this->query('date', '');
    }

    public function getTimeRangeDto(): TimeRangeDto
    {
        return TimeRangeDto::fromPrimitive(
            startTime: $this->getStartTime(),
            endTime: $this->getEndTime(),
        );
    }

    public function getDayOfWeek(): array
    {
        return $this->query('day_of_week', []);
    }

    public function getAllQuery(): array
    {
        return $this->query();
    }

    private function getStartTime(): string
    {
        return $this->query('start_time', '');
    }

    private function getEndTime(): string
    {
        return $this->query('end_time', '');
    }
}
