<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Application\Dto\HospitalDto;
use League\Fractal\TransformerAbstract;

class HospitalTransformer extends TransformerAbstract
{
    /**
     * @OA\Schema(
     *     schema="Response/Hospital",
     *     type="object",
     *     @OA\Property(
     *         property="id",
     *         type="string",
     *         description="病院ID",
     *         example="e23c0dce-5d69-4572-a283-3643d69350bc",
     *     ),
     *     @OA\Property(
     *         property="name",
     *         type="string",
     *         description="病院名",
     *         example="テスト病院",
     *     ),
     *     @OA\Property(
     *         property="zipcode",
     *         type="string",
     *         description="郵便番号",
     *         example="1234567",
     *     ),
     *     @OA\Property(
     *         property="address",
     *         type="string",
     *         description="住所",
     *         example="山梨県工藤市南区津田町加藤5-1-5",
     *     ),
     *     @OA\Property(
     *         property="phone",
     *         type="string",
     *         description="電話番号",
     *         example="0123456789",
     *     ),
     *     @OA\Property(
     *         property="is_public",
     *         type="boolean",
     *         description="公開フラグ",
     *         example=true,
     *     )
     * )
     */
    public function transform(HospitalDto $hospitalDto)
    {
        return [
            'id'        => $hospitalDto->getPublicId()->getValue(),
            'name'      => $hospitalDto->getName()->getValue(),
            'zipcode'   => $hospitalDto->getZipcode()->getValue(),
            'address'   => $hospitalDto->getAddress()->getValue(),
            'phone'     => $hospitalDto->getPhone()->getValue(),
            'is_public' => $hospitalDto->getIsPublished()->getValue(),
        ];
    }
}
