<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Base\ApiRequest;

class HospitalSaveRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @OA\Schema(
     *     schema="Requests/Hospital/HospitalSaveRequest",
     *     type="object",
     *     required={"name", "zipcode", "address", "phone", "is_published"},
     *     description="病院情報の保存",
     *     @OA\Property(
     *         property="name",
     *         type="string",
     *         description="名前"
     *     ),
     *     @OA\Property(
     *         property="zipcode",
     *         type="string",
     *         description="郵便番号 (7桁)"
     *     ),
     *     @OA\Property(
     *         property="address",
     *         type="string",
     *         description="住所",
     *         maxLength=255
     *     ),
     *     @OA\Property(
     *         property="phone",
     *         type="string",
     *         description="電話番号 (10または11桁)"
     *     ),
     *     @OA\Property(
     *         property="is_published",
     *         type="boolean",
     *         description="公開状態"
     *     )
     * )
     */
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
