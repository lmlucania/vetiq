<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Base\ApiRequest;

class UpdateVetRequest extends ApiRequest
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
     *     schema="Requests/Hospital/UpdateVetRequest",
     *     type="object",
     *     required={"last_name", "first_name", "accept_appointment", "remark"},
     *     description="獣医師の更新",
     *     @OA\Property(
     *          property="last_name",
     *          type="string",
     *          description="名前（姓）",
     *          example="山田",
     *     ),
     *     @OA\Property(
     *          property="first_name",
     *          type="string",
     *          description="名前（名）",
     *          example="太郎",
     *     ),
     *     @OA\Property(
     *          property="accept_appointment",
     *          type="boolean",
     *          description="指名予約可否フラグ",
     *          example=true,
     *     ),
     *     @OA\Property(
     *          property="remark",
     *          type="string",
     *          description="備考",
     *          example="皮膚科専門医",
     *     )
     * )
     */
    public function rules(): array
    {
        return [
            'last_name'          => 'required|string',
            'first_name'         => 'required|string',
            'accept_appointment' => 'required|boolean',
            'remark'             => 'required|string',
            'route_params.vet'   => 'required|uuid|exists:vets,uuid',
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
