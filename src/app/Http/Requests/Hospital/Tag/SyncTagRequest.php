<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital\Tag;

use App\Http\Requests\Base\ApiRequest;

class SyncTagRequest extends ApiRequest
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
            'ids'   => 'present|array',
            'ids.*' => 'integer|distinct',
        ];
    }

    public function getIds(): array
    {
        return $this->validated('ids');
    }
}
