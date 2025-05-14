<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Base\PaginationRequest;

class IndexMenuRequest extends PaginationRequest
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
            'sort'    => 'nullable|array',
            'sort.*'  => 'string',
            'keyword' => 'nullable|string',
        ];
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
