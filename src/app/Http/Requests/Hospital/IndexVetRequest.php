<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Base\ApiRequest;

class IndexVetRequest extends ApiRequest
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
            'page'     => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:500',
            'sort'     => 'nullable|array',
            'sort.*'   => 'string',
            'keyword'  => 'nullable|string',
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

    public function getKeyword(): string
    {
        return $this->query('keyword', '');
    }

    public function getAllQuery(): array
    {
        return $this->query();
    }
}
