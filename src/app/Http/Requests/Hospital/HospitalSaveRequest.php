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
     *         description="病院名",
     *         example="テスト病院"
     *     ),
     *     @OA\Property(
     *         property="zipcode",
     *         type="string",
     *         description="郵便番号 (7桁)",
     *         example=1234567
     *     ),
     *     @OA\Property(
     *         property="address",
     *         type="string",
     *         description="住所",
     *         example="東京都新宿区西新宿2-8-1"
     *     ),
     *     @OA\Property(
     *         property="phone",
     *         type="string",
     *         description="電話番号 (10または11桁)",
     *         example="0123456789"
     *     ),
     *     @OA\Property(
     *         property="is_published",
     *         type="boolean",
     *         description="公開状態",
     *         example=true
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
