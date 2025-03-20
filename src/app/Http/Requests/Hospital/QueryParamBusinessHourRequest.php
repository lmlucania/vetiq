<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Base\ApiRequest;

class QueryParamBusinessHourRequest extends ApiRequest
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
            'route_params.business_hour' => 'required|uuid|exists:business_hours,uuid',
        ];
    }
}
