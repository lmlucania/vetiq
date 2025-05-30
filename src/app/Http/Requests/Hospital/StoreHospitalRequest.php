<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Base\ApiRequest;

class StoreHospitalRequest extends ApiRequest
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
            'name'         => 'required|string',
            'zipcode'      => 'required|string|numeric|digits:7',
            'address'      => 'required|string|max:255',
            'phone'        => 'required|string|numeric|digits_between:10,11',
            'is_published' => 'required|boolean',
        ];
    }
}
