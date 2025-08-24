<?php

declare(strict_types=1);

namespace App\Http\Requests\User\HospitalInfo;

use App\Http\Requests\Base\ApiRequest;

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
            'is_open_today' => ['nullable', 'bool'],
            'date' => ['nullable', 'date_format:Y-m-d'],
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

    public function getAllQuery(): array
    {
        return $this->query();
    }
}
