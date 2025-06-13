<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital\ExceptionHour;

use App\Http\Requests\Base\ApiRequest;

class IndexExceptionHourRequest extends ApiRequest
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
            'year' => 'nullable|integer|digits:4|min:2000|max:2100',
        ];
    }

    public function validationData(): array
    {
        return [
            'year' => $this->route('year'),
        ];
    }
}
