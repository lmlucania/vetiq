<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital\Vet;

use App\Http\Requests\Base\ApiRequest;

class StoreVetRequest extends ApiRequest
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
            'last_name'          => 'required|string',
            'first_name'         => 'required|string',
            'accept_appointment' => 'required|boolean',
            'remark'             => 'required|string',
        ];
    }

    /**
     * 名前（姓）
     * @return string
     */
    public function getLastName():string
    {
        return $this->validated('last_name');
    }

    /**
     * 名前（名）
     * @return string
     */
    public function getFirstName():string
    {
        return $this->validated('first_name');
    }

    /**
     * 指名予約可否フラグ
     * @return bool
     */
    public function getAcceptAppointment():bool
    {
        return $this->validated('accept_appointment');
    }

    /**
     * 備考
     * @return string
     */
    public function getRemark():string
    {
        return $this->validated('remark');
    }
}
